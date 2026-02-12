<?php

use App\Domain\Documentation\Access\Entities\DocAccessLink;
use App\Domain\Documentation\Access\Entities\DocRole;
use App\Domain\Documentation\Access\Events\AccessLinkCreated;
use App\Domain\Documentation\Access\Events\AccessLinkRevoked;
use App\Http\Controllers\Docs\DocAccessLinkController;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;

describe(class_basename(DocAccessLinkController::class), function (): void {
    it('creates an access link and returns plain token in session', function (): void {
        Event::fake([AccessLinkCreated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Partner',
            'scopes' => ['payments'],
            'is_default' => false,
        ]);

        $this->actingAs($user)
            ->post("/projects/{$project->slug}/doc-links", [
                'doc_role_id' => $role->ulid,
                'name' => 'Partner Link',
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Access link created.')
            ->assertSessionHas('plain_token');

        $link = DocAccessLink::where('project_id', $project->id)->first();
        expect($link->getName())->toBe('Partner Link')
            ->and($link->getDocRoleId())->toBe($role->id);
    });

    it('creates access link with expiry', function (): void {
        Event::fake([AccessLinkCreated::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Temp',
            'scopes' => ['*'],
            'is_default' => false,
        ]);

        $expiresAt = now()->addDays(7)->toDateTimeString();
        $this->actingAs($user)
            ->post("/projects/{$project->slug}/doc-links", [
                'doc_role_id' => $role->ulid,
                'name' => 'Temp Link',
                'expires_at' => $expiresAt,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $link = DocAccessLink::where('project_id', $project->id)->first();
        expect($link->getExpiresAt())->not->toBeNull();
    });

    it('revokes an access link', function (): void {
        Event::fake([AccessLinkRevoked::class]);
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Partner',
            'scopes' => ['*'],
            'is_default' => false,
        ]);
        $link = DocAccessLink::create([
            'project_id' => $project->id,
            'doc_role_id' => $role->id,
            'name' => 'To Revoke',
            'token' => hash('sha256', 'test-token'),
        ]);

        $this->actingAs($user)
            ->delete("/projects/{$project->slug}/doc-links/{$link->ulid}")
            ->assertRedirect()
            ->assertSessionHas('success', 'Access link revoked.');

        expect(DocAccessLink::find($link->id))->toBeNull();
    });

    it('requires authentication for store', function (): void {
        $project = Project::factory()->create();

        $this->post("/projects/{$project->slug}/doc-links", [
            'doc_role_id' => 'fake-ulid',
            'name' => 'Test',
        ])->assertRedirect('/login');
    });

    it('returns 403 for non-owner on store', function (): void {
        $user = User::factory()->create();
        $otherProject = Project::factory()->create();

        $this->actingAs($user)
            ->post("/projects/{$otherProject->slug}/doc-links", [
                'doc_role_id' => 'fake-ulid',
                'name' => 'Test',
            ])
            ->assertForbidden();
    });

    it('returns 403 for non-owner on destroy', function (): void {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherProject = Project::factory()->for($otherUser)->create();
        $role = DocRole::create([
            'project_id' => $otherProject->id,
            'name' => 'Partner',
            'scopes' => ['*'],
            'is_default' => false,
        ]);
        $link = DocAccessLink::create([
            'project_id' => $otherProject->id,
            'doc_role_id' => $role->id,
            'name' => 'Protected',
            'token' => hash('sha256', 'test-token'),
        ]);

        $this->actingAs($user)
            ->delete("/projects/{$otherProject->slug}/doc-links/{$link->ulid}")
            ->assertForbidden();
    });

    it('validates doc_role_id is required', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)
            ->post("/projects/{$project->slug}/doc-links", [
                'name' => 'Test',
            ])
            ->assertSessionHasErrors('doc_role_id');
    });

    it('validates name is required', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)
            ->post("/projects/{$project->slug}/doc-links", [
                'doc_role_id' => 'fake-ulid',
            ])
            ->assertSessionHasErrors('name');
    });
})->covers(DocAccessLinkController::class);
