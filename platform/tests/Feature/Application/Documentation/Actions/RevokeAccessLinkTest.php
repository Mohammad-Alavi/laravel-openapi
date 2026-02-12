<?php

use App\Application\Documentation\Actions\RevokeAccessLink;
use App\Domain\Documentation\Access\Entities\DocAccessLink;
use App\Domain\Documentation\Access\Entities\DocRole;
use App\Domain\Documentation\Access\Events\AccessLinkRevoked;
use App\Domain\Documentation\Access\ValueObjects\HashedToken;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;

describe(class_basename(RevokeAccessLink::class), function (): void {
    it('deletes an access link and dispatches event', function (): void {
        Event::fake([AccessLinkRevoked::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Partner',
            'scopes' => ['payments'],
            'is_default' => false,
        ]);
        $link = DocAccessLink::create([
            'project_id' => $project->id,
            'doc_role_id' => $role->id,
            'name' => 'Partner Link',
            'token' => HashedToken::fromPlain('test-token')->toString(),
        ]);

        $action = app(RevokeAccessLink::class);
        $action->execute($link->ulid);

        expect(DocAccessLink::find($link->id))->toBeNull();

        Event::assertDispatched(AccessLinkRevoked::class, function (AccessLinkRevoked $event) use ($project): bool {
            return $event->projectId === $project->id
                && $event->linkName === 'Partner Link';
        });
    });
})->covers(RevokeAccessLink::class);
