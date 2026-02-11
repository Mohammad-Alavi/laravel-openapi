<?php

declare(strict_types=1);

use App\Application\Documentation\Actions\CreateAccessLink;
use App\Domain\Documentation\Access\Entities\DocAccessLink;
use App\Domain\Documentation\Access\Entities\DocRole;
use App\Domain\Documentation\Access\Events\AccessLinkCreated;
use App\Domain\Documentation\Access\ValueObjects\CreateAccessLinkResult;
use App\Domain\Documentation\Access\ValueObjects\PlainToken;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;

describe(class_basename(CreateAccessLink::class), function (): void {
    it('creates an access link and returns plain token', function (): void {
        Event::fake([AccessLinkCreated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Partner',
            'scopes' => ['payments'],
            'is_default' => false,
        ]);

        $action = app(CreateAccessLink::class);
        $result = $action->execute($project->id, $role->id, 'Partner Link');

        expect($result)->toBeInstanceOf(CreateAccessLinkResult::class)
            ->and($result->token)->toBeInstanceOf(PlainToken::class)
            ->and($result->link)->toBeInstanceOf(DocAccessLink::class)
            ->and($result->link->getName())->toBe('Partner Link')
            ->and($result->link->getDocRoleId())->toBe($role->id);

        // Verify token matches the stored hash
        $storedLink = DocAccessLink::first();
        expect($storedLink->verifyToken($result->token->toString()))->toBeTrue();

        Event::assertDispatched(AccessLinkCreated::class, function (AccessLinkCreated $event) use ($project): bool {
            return $event->projectId === $project->id
                && $event->roleName === 'Partner'
                && $event->hasExpiry === false;
        });
    });

    it('creates link with expiry date', function (): void {
        Event::fake([AccessLinkCreated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Temp',
            'scopes' => ['*'],
            'is_default' => false,
        ]);

        $action = app(CreateAccessLink::class);
        $expiresAt = now()->addDays(7)->toDateTimeString();
        $result = $action->execute($project->id, $role->id, 'Temp Link', $expiresAt);

        expect($result->link->getExpiresAt())->not->toBeNull();

        Event::assertDispatched(AccessLinkCreated::class, function (AccessLinkCreated $event): bool {
            return $event->hasExpiry === true;
        });
    });
})->covers(CreateAccessLink::class);
