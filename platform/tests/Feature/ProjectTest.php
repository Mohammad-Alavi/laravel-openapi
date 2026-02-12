<?php

use App\Domain\Documentation\Access\Entities\DocSetting;
use App\Domain\Documentation\Access\Enums\DocVisibility;
use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Http;

describe('Project CRUD', function (): void {
    describe('index', function (): void {
        it('lists only the authenticated user\'s projects', function (): void {
            $user = User::factory()->create();
            $ownProject = Project::factory()->for($user)->create();
            $otherProject = Project::factory()->create();

            $response = $this->actingAs($user)->get('/projects');

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Projects/Index')
                    ->has('projects.data', 1)
                    ->where('projects.data.0.id', $ownProject->ulid)
                    ->where('projects.data.0.has_builds', false)
                    ->missing('projects.data.0.github_webhook_id')
                    ->missing('projects.data.0.github_webhook_secret')
                    ->missing('projects.data.0.user_id')
                    ->missing('projects.data.0.latest_build_id')
                );
        });

        it('shows empty state when user has no projects', function (): void {
            $user = User::factory()->create();

            $response = $this->actingAs($user)->get('/projects');

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Projects/Index')
                    ->has('projects.data', 0)
                );
        });

        it('includes project status counts in the index response', function (): void {
            $user = User::factory()->create();
            Project::factory()->for($user)->count(2)->create(['status' => ProjectStatus::Active]);
            Project::factory()->for($user)->count(2)->create(['status' => ProjectStatus::Paused]);
            Project::factory()->for($user)->count(2)->create(['status' => ProjectStatus::Building]);

            $response = $this->actingAs($user)->get('/projects');

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Projects/Index')
                    ->where('stats.total', 6)
                    ->where('stats.active', 2)
                    ->where('stats.paused', 2)
                    ->where('stats.building', 2)
                );
        });

        it('paginates the project list', function (): void {
            $user = User::factory()->create();
            Project::factory()->for($user)->count(15)->create();

            $response = $this->actingAs($user)->get('/projects');

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Projects/Index')
                    ->has('projects.data', 12)
                    ->where('projects.last_page', 2)
                );
        });

        it('filters projects by name search', function (): void {
            $user = User::factory()->create();
            Project::factory()->for($user)->create(['name' => 'My API Project']);
            Project::factory()->for($user)->create(['name' => 'Another App']);

            $response = $this->actingAs($user)->get('/projects?search=API');

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Projects/Index')
                    ->has('projects.data', 1)
                    ->where('projects.data.0.name', 'My API Project')
                );
        });

        it('filters projects by status', function (): void {
            $user = User::factory()->create();
            Project::factory()->for($user)->create(['status' => ProjectStatus::Active]);
            Project::factory()->for($user)->create(['status' => ProjectStatus::Paused]);

            $response = $this->actingAs($user)->get('/projects?status=paused');

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Projects/Index')
                    ->has('projects.data', 1)
                    ->where('projects.data.0.status', 'paused')
                );
        });
    });

    describe('create', function (): void {
        it('shows the create project form', function (): void {
            $user = User::factory()->create();

            $response = $this->actingAs($user)->get('/projects/create');

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Projects/Create')
                );
        });
    });

    describe('store', function (): void {
        it('creates a project and generates a slug', function (): void {
            Http::fake(['api.github.com/*' => Http::response(['id' => 1], 201)]);
            $user = User::factory()->create();

            $response = $this->actingAs($user)->post('/projects', [
                'name' => 'My Awesome Project',
                'description' => 'A great project',
                'github_repo_url' => 'https://github.com/user/repo',
                'github_branch' => 'main',
            ]);

            $response->assertRedirect('/projects');

            $this->assertDatabaseHas('projects', [
                'user_id' => $user->id,
                'name' => 'My Awesome Project',
                'slug' => 'my-awesome-project',
                'github_repo_url' => 'https://github.com/user/repo',
            ]);
        });

        it('validates required fields', function (): void {
            $user = User::factory()->create();

            $response = $this->actingAs($user)->post('/projects', []);

            $response->assertSessionHasErrors(['name', 'github_repo_url']);
        });

        it('validates GitHub URL format', function (): void {
            $user = User::factory()->create();

            $response = $this->actingAs($user)->post('/projects', [
                'name' => 'Test',
                'github_repo_url' => 'https://not-github.com/user/repo',
            ]);

            $response->assertSessionHasErrors(['github_repo_url']);
        });
    });

    describe('show', function (): void {
        it('shows the project to its owner', function (): void {
            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create();

            $response = $this->actingAs($user)->get("/projects/{$project->slug}");

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Projects/Show')
                    ->where('project.id', $project->ulid)
                    ->where('project.name', $project->name)
                    ->where('project.has_builds', false)
                    ->missing('project.github_webhook_id')
                    ->missing('project.github_webhook_secret')
                    ->missing('project.user_id')
                    ->missing('project.latest_build_id')
                );
        });

        it('does not include project_id in docSetting', function (): void {
            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create();

            $setting = new DocSetting();
            $setting->forceFill([
                'project_id' => $project->id,
                'visibility' => DocVisibility::Public,
            ]);
            $setting->save();

            $response = $this->actingAs($user)->get("/projects/{$project->slug}");

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Projects/Show')
                    ->where('docSetting.visibility', 'public')
                    ->missing('docSetting.project_id')
                );
        });

        it('prevents viewing another user\'s project', function (): void {
            $user = User::factory()->create();
            $otherProject = Project::factory()->create();

            $response = $this->actingAs($user)->get("/projects/{$otherProject->slug}");

            $response->assertForbidden();
        });
    });

    describe('edit', function (): void {
        it('shows the edit form to the project owner', function (): void {
            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create();

            $response = $this->actingAs($user)->get("/projects/{$project->slug}/edit");

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Projects/Edit')
                    ->where('project.id', $project->ulid)
                    ->missing('project.github_webhook_id')
                    ->missing('project.github_webhook_secret')
                    ->missing('project.user_id')
                    ->missing('project.latest_build_id')
                );
        });
    });

    describe('update', function (): void {
        it('updates the project', function (): void {
            Http::fake(['api.github.com/*' => Http::response(['id' => 1], 201)]);
            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create();

            $response = $this->actingAs($user)->put("/projects/{$project->slug}", [
                'name' => 'Updated Name',
                'description' => 'Updated description',
                'github_repo_url' => 'https://github.com/user/updated-repo',
                'github_branch' => 'develop',
            ]);

            $response->assertRedirect("/projects/{$project->slug}");

            expect($project->fresh())
                ->name->toBe('Updated Name')
                ->and($project->fresh()->github_branch)->toBe('develop');
        });

        it('prevents updating another user\'s project', function (): void {
            $user = User::factory()->create();
            $otherProject = Project::factory()->create();

            $response = $this->actingAs($user)->put("/projects/{$otherProject->slug}", [
                'name' => 'Hacked',
            ]);

            $response->assertForbidden();
        });
    });

    describe('destroy', function (): void {
        it('deletes the project', function (): void {
            Http::fake(['api.github.com/*' => Http::response(null, 204)]);
            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create();

            $response = $this->actingAs($user)->delete("/projects/{$project->slug}");

            $response->assertRedirect('/projects');
            $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        });

        it('prevents deleting another user\'s project', function (): void {
            $user = User::factory()->create();
            $otherProject = Project::factory()->create();

            $response = $this->actingAs($user)->delete("/projects/{$otherProject->slug}");

            $response->assertForbidden();
            $this->assertDatabaseHas('projects', ['id' => $otherProject->id]);
        });
    });

    describe('webhook lifecycle', function (): void {
        it('registers a GitHub webhook after creating a project', function (): void {
            Http::fake([
                'api.github.com/repos/user/repo/hooks' => Http::response(['id' => 42], 201),
            ]);

            $user = User::factory()->create();

            $this->actingAs($user)->post('/projects', [
                'name' => 'Webhook Project',
                'github_repo_url' => 'https://github.com/user/repo',
                'github_branch' => 'main',
            ]);

            $project = Project::where('name', 'Webhook Project')->first();

            expect($project->github_webhook_id)->toBe(42)
                ->and($project->github_webhook_secret)->not->toBeNull();

            Http::assertSent(fn ($request) => str_contains($request->url(), 'api.github.com/repos/user/repo/hooks')
                && $request->method() === 'POST');
        });

        it('still creates the project when webhook registration fails', function (): void {
            Http::fake([
                'api.github.com/*' => Http::response(['message' => 'Validation Failed'], 422),
            ]);

            $user = User::factory()->create();

            $this->actingAs($user)->post('/projects', [
                'name' => 'Failing Webhook Project',
                'github_repo_url' => 'https://github.com/user/repo',
                'github_branch' => 'main',
            ]);

            $project = Project::where('name', 'Failing Webhook Project')->first();

            expect($project)->not->toBeNull()
                ->and($project->github_webhook_id)->toBeNull();
        });

        it('re-registers webhook when github_repo_url changes', function (): void {
            Http::fake([
                'api.github.com/repos/user/old-repo/hooks/100' => Http::response(null, 204),
                'api.github.com/repos/user/new-repo/hooks' => Http::response(['id' => 200], 201),
            ]);

            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create([
                'github_repo_url' => 'https://github.com/user/old-repo',
                'github_webhook_id' => 100,
                'github_webhook_secret' => 'old-secret',
            ]);

            $this->actingAs($user)->put("/projects/{$project->slug}", [
                'name' => $project->name,
                'github_repo_url' => 'https://github.com/user/new-repo',
                'github_branch' => 'main',
            ]);

            $project->refresh();

            expect($project->github_webhook_id)->toBe(200)
                ->and($project->github_repo_url)->toBe('https://github.com/user/new-repo');

            Http::assertSent(fn ($request) => str_contains($request->url(), 'old-repo/hooks/100')
                && $request->method() === 'DELETE');
            Http::assertSent(fn ($request) => str_contains($request->url(), 'new-repo/hooks')
                && $request->method() === 'POST');
        });

        it('does not re-register webhook when repo URL stays the same', function (): void {
            Http::fake();

            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create([
                'github_repo_url' => 'https://github.com/user/repo',
                'github_webhook_id' => 100,
                'github_webhook_secret' => 'secret',
            ]);

            $this->actingAs($user)->put("/projects/{$project->slug}", [
                'name' => 'Updated Name Only',
                'github_repo_url' => 'https://github.com/user/repo',
                'github_branch' => 'main',
            ]);

            Http::assertNothingSent();
        });

        it('deregisters webhook before deleting the project', function (): void {
            Http::fake([
                'api.github.com/repos/user/repo/hooks/100' => Http::response(null, 204),
            ]);

            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create([
                'github_repo_url' => 'https://github.com/user/repo',
                'github_webhook_id' => 100,
                'github_webhook_secret' => 'secret',
            ]);

            $this->actingAs($user)->delete("/projects/{$project->slug}");

            Http::assertSent(fn ($request) => str_contains($request->url(), 'hooks/100')
                && $request->method() === 'DELETE');

            $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        });
    });
});
