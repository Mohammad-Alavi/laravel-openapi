<?php

declare(strict_types=1);

use App\Domain\Documentation\Access\Entities\DocRole;
use App\Domain\Documentation\Access\Entities\DocSetting;
use App\Domain\Documentation\Access\Enums\DocVisibility;
use App\Enums\BuildStatus;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

describe('Documentation Management Flow', function (): void {
    it('completes full doc setup: visibility → role → rule → access link', function (): void {
        Storage::fake();

        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create(['status' => BuildStatus::Completed]);
        $project->update(['latest_build_id' => $build->id]);

        // Store a spec for the project
        Storage::put("builds/{$project->id}/{$build->id}/openapi.json", json_encode([
            'openapi' => '3.1.0',
            'info' => ['title' => 'Test API', 'version' => '1.0'],
            'tags' => [['name' => 'Users'], ['name' => 'Orders']],
            'paths' => [
                '/api/users' => ['get' => ['tags' => ['Users'], 'responses' => []]],
                '/api/orders' => ['get' => ['tags' => ['Orders'], 'responses' => []]],
            ],
        ]));

        // Step 1: View project — docs section visible
        $this->actingAs($user)->get("/projects/{$project->slug}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('project.has_builds', true)
                ->has('specTags', 2)
                ->has('specPaths', 2)
                ->where('specTags.0.name', 'Users')
                ->where('specTags.1.name', 'Orders')
            );

        // Step 2: Set visibility to public
        $this->actingAs($user)
            ->put("/projects/{$project->slug}/doc-settings", ['visibility' => 'public'])
            ->assertRedirect();

        $this->actingAs($user)->get("/projects/{$project->slug}")
            ->assertInertia(fn ($page) => $page
                ->where('docSetting.visibility', 'public')
            );

        // Step 3: Create a role with scopes
        $this->actingAs($user)
            ->post("/projects/{$project->slug}/doc-roles", [
                'name' => 'Partner',
                'scopes' => ['Users'],
                'is_default' => false,
            ])
            ->assertRedirect();

        $this->actingAs($user)->get("/projects/{$project->slug}")
            ->assertInertia(fn ($page) => $page
                ->has('docRoles', 1)
                ->where('docRoles.0.name', 'Partner')
                ->where('docRoles.0.scopes', ['Users'])
            );

        // Step 4: Create a visibility rule
        $this->actingAs($user)
            ->post("/projects/{$project->slug}/doc-rules", [
                'rule_type' => 'tag',
                'identifier' => 'Orders',
                'visibility' => 'hidden',
            ])
            ->assertRedirect();

        $this->actingAs($user)->get("/projects/{$project->slug}")
            ->assertInertia(fn ($page) => $page
                ->has('docRules', 1)
                ->where('docRules.0.identifier', 'Orders')
                ->where('docRules.0.visibility', 'hidden')
            );

        // Step 5: Create an access link
        $roleId = DocRole::where('project_id', $project->id)->first()->ulid;

        $response = $this->actingAs($user)
            ->post("/projects/{$project->slug}/doc-links", [
                'doc_role_id' => $roleId,
                'name' => 'Partner Portal Link',
                'expires_at' => null,
            ]);
        $response->assertRedirect();

        $this->actingAs($user)->get("/projects/{$project->slug}")
            ->assertInertia(fn ($page) => $page
                ->has('docLinks', 1)
                ->where('docLinks.0.name', 'Partner Portal Link')
            );

        // Step 6: Verify the docs page is accessible anonymously (public)
        $this->get("/docs/{$project->slug}")
            ->assertOk();
    });

    it('blocks doc management when project has no builds', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)->get("/projects/{$project->slug}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('project.has_builds', false)
                ->has('specTags', 0)
                ->has('specPaths', 0)
            );
    });

    it('defaults to private docs for anonymous access', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create(['status' => BuildStatus::Completed]);
        $project->update(['latest_build_id' => $build->id]);

        // No doc setting configured → defaults to private → 404 for anonymous
        $this->get("/docs/{$project->slug}")
            ->assertNotFound();
    });
});
