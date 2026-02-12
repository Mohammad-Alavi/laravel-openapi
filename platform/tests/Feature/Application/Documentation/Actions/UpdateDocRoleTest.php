<?php

use App\Application\Documentation\Actions\UpdateDocRole;
use App\Domain\Documentation\Access\Entities\DocRole;
use App\Domain\Documentation\Access\Events\DocRoleUpdated;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;

describe(class_basename(UpdateDocRole::class), function (): void {
    it('updates a doc role and dispatches event', function (): void {
        Event::fake([DocRoleUpdated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Partner',
            'scopes' => ['payments'],
            'is_default' => false,
        ]);

        $action = app(UpdateDocRole::class);
        $updated = $action->execute($role->ulid, ['name' => 'Premium Partner', 'scopes' => ['payments', 'webhooks']]);

        expect($updated->getName())->toBe('Premium Partner')
            ->and($updated->getScopes()->toArray())->toBe(['payments', 'webhooks']);

        Event::assertDispatched(DocRoleUpdated::class, function (DocRoleUpdated $event) use ($project): bool {
            return $event->projectId === $project->id
                && $event->roleName === 'Premium Partner'
                && $event->scopesChanged === true;
        });
    });

    it('detects when scopes did not change', function (): void {
        Event::fake([DocRoleUpdated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Partner',
            'scopes' => ['payments'],
            'is_default' => false,
        ]);

        $action = app(UpdateDocRole::class);
        $action->execute($role->ulid, ['name' => 'New Name']);

        Event::assertDispatched(DocRoleUpdated::class, function (DocRoleUpdated $event): bool {
            return $event->scopesChanged === false;
        });
    });
})->covers(UpdateDocRole::class);
