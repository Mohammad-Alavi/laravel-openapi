<?php

declare(strict_types=1);

use App\Application\Documentation\Actions\CreateDocRole;
use App\Domain\Documentation\Access\Contracts\DocRole;
use App\Domain\Documentation\Access\Events\DocRoleCreated;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;

describe(class_basename(CreateDocRole::class), function (): void {
    it('creates a doc role and dispatches event', function (): void {
        Event::fake([DocRoleCreated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $action = app(CreateDocRole::class);
        $role = $action->execute($project->id, [
            'name' => 'Partner',
            'scopes' => ['payments', 'webhooks'],
            'is_default' => false,
        ]);

        expect($role)->toBeInstanceOf(DocRole::class)
            ->and($role->getName())->toBe('Partner')
            ->and($role->getScopes()->toArray())->toBe(['payments', 'webhooks'])
            ->and($role->isDefault())->toBeFalse();

        Event::assertDispatched(DocRoleCreated::class, function (DocRoleCreated $event) use ($project): bool {
            return $event->projectId === $project->id
                && $event->roleName === 'Partner'
                && $event->scopeCount === 2
                && $event->hasWildcards === false;
        });
    });

    it('detects wildcard scopes in event', function (): void {
        Event::fake([DocRoleCreated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $action = app(CreateDocRole::class);
        $action->execute($project->id, [
            'name' => 'Full Access',
            'scopes' => ['*'],
        ]);

        Event::assertDispatched(DocRoleCreated::class, function (DocRoleCreated $event): bool {
            return $event->hasWildcards === true;
        });
    });
})->covers(CreateDocRole::class);
