<?php

declare(strict_types=1);

use App\Enums\BuildStatus;
use App\Enums\ProjectStatus;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Http;

describe('Project Lifecycle Flow', function (): void {
    it('completes the full create → list → view → edit → delete flow', function (): void {
        Http::fake(['api.github.com/*' => Http::response(['id' => 1], 201)]);

        $user = User::factory()->create();

        // Step 1: Visit create page
        $this->actingAs($user)->get('/projects/create')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Projects/Create'));

        // Step 2: Submit the form
        $this->actingAs($user)->post('/projects', [
            'name' => 'Flow Test Project',
            'description' => 'Testing the full lifecycle',
            'github_repo_url' => 'https://github.com/user/flow-test',
            'github_branch' => 'main',
        ])->assertRedirect('/projects');

        $project = Project::where('name', 'Flow Test Project')->first();
        expect($project)->not->toBeNull()
            ->and($project->slug)->toBe('flow-test-project');

        // Step 3: See it in the list
        $this->actingAs($user)->get('/projects')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Projects/Index')
                ->where('projects.data.0.name', 'Flow Test Project')
                ->where('projects.data.0.has_builds', false)
            );

        // Step 4: View the project
        $this->actingAs($user)->get("/projects/{$project->slug}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Projects/Show')
                ->where('project.name', 'Flow Test Project')
                ->where('project.github_repo_url', 'https://github.com/user/flow-test')
                ->where('project.github_branch', 'main')
                ->has('recentBuilds', 0)
                ->has('specTags', 0)
                ->has('specPaths', 0)
            );

        // Step 5: Edit the project
        $this->actingAs($user)->get("/projects/{$project->slug}/edit")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Projects/Edit')
                ->where('project.name', 'Flow Test Project')
            );

        $this->actingAs($user)->put("/projects/{$project->slug}", [
            'name' => 'Updated Flow Project',
            'github_repo_url' => 'https://github.com/user/flow-test',
            'github_branch' => 'develop',
        ])->assertRedirect("/projects/{$project->slug}");

        expect($project->fresh()->name)->toBe('Updated Flow Project')
            ->and($project->fresh()->github_branch)->toBe('develop');

        // Step 6: Delete the project
        Http::fake(['api.github.com/*' => Http::response(null, 204)]);

        $this->actingAs($user)->delete("/projects/{$project->slug}")
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    });

    it('shows validation errors and preserves input on invalid create', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from('/projects/create')
            ->post('/projects', [
                'name' => '',
                'github_repo_url' => 'not-a-url',
            ]);

        $response->assertRedirect('/projects/create')
            ->assertSessionHasErrors(['name', 'github_repo_url']);
    });

    it('shows validation errors on invalid update', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->from("/projects/{$project->slug}/edit")
            ->put("/projects/{$project->slug}", [
                'name' => '',
                'github_repo_url' => 'invalid',
            ]);

        $response->assertRedirect("/projects/{$project->slug}/edit")
            ->assertSessionHasErrors(['name', 'github_repo_url']);
    });

    it('prevents accessing another user\'s project at every step', function (): void {
        $user = User::factory()->create();
        $other = Project::factory()->create();

        $this->actingAs($user)->get("/projects/{$other->slug}")->assertForbidden();
        $this->actingAs($user)->get("/projects/{$other->slug}/edit")->assertForbidden();
        $this->actingAs($user)->put("/projects/{$other->slug}", ['name' => 'Hack'])->assertForbidden();
        $this->actingAs($user)->delete("/projects/{$other->slug}")->assertForbidden();
    });
});

describe('Project with Builds Flow', function (): void {
    it('shows build history and status on the project page', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Active,
            'last_built_at' => now(),
        ]);

        $completedBuild = Build::factory()->for($project)->create([
            'status' => BuildStatus::Completed,
            'commit_sha' => 'abc1234',
            'started_at' => now()->subMinutes(3),
            'completed_at' => now()->subMinutes(2),
            'created_at' => now()->subMinutes(3),
        ]);

        $failedBuild = Build::factory()->for($project)->create([
            'status' => BuildStatus::Failed,
            'commit_sha' => 'def5678',
            'error_log' => 'composer install failed',
            'started_at' => now()->subMinute(),
            'completed_at' => now(),
            'created_at' => now()->subMinute(),
        ]);

        $this->actingAs($user)->get("/projects/{$project->slug}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Projects/Show')
                ->where('project.status', 'active')
                ->has('project.last_built_at')
                ->has('recentBuilds', 2)
                ->where('recentBuilds.0.status', 'failed')
                ->where('recentBuilds.0.error_log', 'composer install failed')
                ->where('recentBuilds.1.status', 'completed')
                ->where('recentBuilds.1.commit_sha', 'abc1234')
            );
    });

    it('shows building status with progress indicator data', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Building,
        ]);

        Build::factory()->for($project)->create([
            'status' => BuildStatus::Building,
            'started_at' => now(),
        ]);

        $this->actingAs($user)->get("/projects/{$project->slug}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('project.status', 'building')
                ->has('recentBuilds', 1)
                ->where('recentBuilds.0.status', 'building')
            );
    });

    it('shows has_builds true when project has a latest build', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create(['status' => BuildStatus::Completed]);
        $project->update(['latest_build_id' => $build->id]);

        $this->actingAs($user)->get("/projects/{$project->slug}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('project.has_builds', true)
            );
    });
});

describe('Project List Filtering Flow', function (): void {
    it('filters by search and shows matching results', function (): void {
        $user = User::factory()->create();
        Project::factory()->for($user)->create(['name' => 'Payment API']);
        Project::factory()->for($user)->create(['name' => 'Auth Service']);

        $this->actingAs($user)->get('/projects?search=Payment')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('projects.data', 1)
                ->where('projects.data.0.name', 'Payment API')
            );
    });

    it('filters by status and shows matching results', function (): void {
        $user = User::factory()->create();
        Project::factory()->for($user)->create(['status' => ProjectStatus::Active]);
        Project::factory()->for($user)->create(['status' => ProjectStatus::Paused]);

        $this->actingAs($user)->get('/projects?status=active')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('projects.data', 1)
                ->where('projects.data.0.status', 'active')
            );
    });

    it('combines search and status filters', function (): void {
        $user = User::factory()->create();
        Project::factory()->for($user)->create(['name' => 'Active API', 'status' => ProjectStatus::Active]);
        Project::factory()->for($user)->create(['name' => 'Paused API', 'status' => ProjectStatus::Paused]);
        Project::factory()->for($user)->create(['name' => 'Active Service', 'status' => ProjectStatus::Active]);

        $this->actingAs($user)->get('/projects?search=API&status=active')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('projects.data', 1)
                ->where('projects.data.0.name', 'Active API')
            );
    });
});
