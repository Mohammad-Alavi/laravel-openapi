<?php

declare(strict_types=1);

use App\Domain\Documentation\Access\Entities\DocRole;
use App\Domain\Documentation\Access\Events\DocRoleCreated;
use App\Domain\Documentation\Access\Events\DocRoleDeleted;
use App\Domain\Documentation\Access\Events\DocRoleUpdated;
use App\Http\Controllers\Docs\DocRoleController;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;

describe(class_basename(DocRoleController::class), function (): void {
    it('creates a doc role', function (): void {
        Event::fake([DocRoleCreated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)
            ->post("/projects/{$project->slug}/doc-roles", [
                'name' => 'Partner',
                'scopes' => ['payments', 'webhooks'],
                'is_default' => false,
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Role created.');

        $role = DocRole::where('project_id', $project->id)->first();
        expect($role->getName())->toBe('Partner')
            ->and($role->scopes)->toBe(['payments', 'webhooks'])
            ->and($role->isDefault())->toBeFalse();
    });

    it('updates a doc role', function (): void {
        Event::fake([DocRoleUpdated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Partner',
            'scopes' => ['payments'],
            'is_default' => false,
        ]);

        $this->actingAs($user)
            ->put("/projects/{$project->slug}/doc-roles/{$role->ulid}", [
                'name' => 'Updated Partner',
                'scopes' => ['payments', 'users'],
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Role updated.');

        $role->refresh();
        expect($role->getName())->toBe('Updated Partner')
            ->and($role->scopes)->toBe(['payments', 'users']);
    });

    it('deletes a doc role', function (): void {
        Event::fake([DocRoleDeleted::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'ToDelete',
            'scopes' => ['*'],
            'is_default' => false,
        ]);

        $this->actingAs($user)
            ->delete("/projects/{$project->slug}/doc-roles/{$role->ulid}")
            ->assertRedirect()
            ->assertSessionHas('success', 'Role deleted.');

        expect(DocRole::find($role->id))->toBeNull();
    });

    it('requires authentication for store', function (): void {
        $project = Project::factory()->create();

        $this->post("/projects/{$project->slug}/doc-roles", [
            'name' => 'Test',
            'scopes' => ['*'],
        ])->assertRedirect('/login');
    });

    it('returns 403 for non-owner on store', function (): void {
        $user = User::factory()->create();
        $otherProject = Project::factory()->create();

        $this->actingAs($user)
            ->post("/projects/{$otherProject->slug}/doc-roles", [
                'name' => 'Test',
                'scopes' => ['*'],
            ])
            ->assertForbidden();
    });

    it('returns 403 for non-owner on destroy', function (): void {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherProject = Project::factory()->for($otherUser)->create();
        $role = DocRole::create([
            'project_id' => $otherProject->id,
            'name' => 'Test',
            'scopes' => ['*'],
            'is_default' => false,
        ]);

        $this->actingAs($user)
            ->delete("/projects/{$otherProject->slug}/doc-roles/{$role->ulid}")
            ->assertForbidden();
    });

    it('validates name is required', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)
            ->post("/projects/{$project->slug}/doc-roles", [
                'scopes' => ['*'],
            ])
            ->assertSessionHasErrors('name');
    });

    it('validates scopes is required', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)
            ->post("/projects/{$project->slug}/doc-roles", [
                'name' => 'Test',
            ])
            ->assertSessionHasErrors('scopes');
    });
})->covers(DocRoleController::class);
