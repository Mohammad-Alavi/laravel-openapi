<?php

use App\Application\Documentation\Actions\DeleteDocRole;
use App\Domain\Documentation\Access\Entities\DocRole;
use App\Domain\Documentation\Access\Events\DocRoleDeleted;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;

describe(class_basename(DeleteDocRole::class), function (): void {
    it('deletes a doc role and dispatches event', function (): void {
        Event::fake([DocRoleDeleted::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Partner',
            'scopes' => ['payments'],
            'is_default' => false,
        ]);

        $action = app(DeleteDocRole::class);
        $action->execute($role->ulid);

        expect(DocRole::find($role->id))->toBeNull();

        Event::assertDispatched(DocRoleDeleted::class, function (DocRoleDeleted $event) use ($project): bool {
            return $event->projectId === $project->id
                && $event->roleName === 'Partner';
        });
    });
})->covers(DeleteDocRole::class);
