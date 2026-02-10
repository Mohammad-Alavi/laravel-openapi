<?php

declare(strict_types=1);

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;

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
                    ->has('projects', 1)
                    ->where('projects.0.id', $ownProject->id)
                );
        });

        it('shows empty state when user has no projects', function (): void {
            $user = User::factory()->create();

            $response = $this->actingAs($user)->get('/projects');

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Projects/Index')
                    ->has('projects', 0)
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

            $response = $this->actingAs($user)->get("/projects/{$project->id}");

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Projects/Show')
                    ->where('project.id', $project->id)
                    ->where('project.name', $project->name)
                );
        });

        it('prevents viewing another user\'s project', function (): void {
            $user = User::factory()->create();
            $otherProject = Project::factory()->create();

            $response = $this->actingAs($user)->get("/projects/{$otherProject->id}");

            $response->assertForbidden();
        });
    });

    describe('edit', function (): void {
        it('shows the edit form to the project owner', function (): void {
            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create();

            $response = $this->actingAs($user)->get("/projects/{$project->id}/edit");

            $response->assertOk()
                ->assertInertia(fn ($page) => $page
                    ->component('Projects/Edit')
                    ->where('project.id', $project->id)
                );
        });
    });

    describe('update', function (): void {
        it('updates the project', function (): void {
            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create();

            $response = $this->actingAs($user)->put("/projects/{$project->id}", [
                'name' => 'Updated Name',
                'description' => 'Updated description',
                'github_repo_url' => 'https://github.com/user/updated-repo',
                'github_branch' => 'develop',
                'status' => ProjectStatus::Paused->value,
            ]);

            $response->assertRedirect("/projects/{$project->id}");

            expect($project->fresh())
                ->name->toBe('Updated Name')
                ->and($project->fresh())->status->toBe(ProjectStatus::Paused);
        });

        it('prevents updating another user\'s project', function (): void {
            $user = User::factory()->create();
            $otherProject = Project::factory()->create();

            $response = $this->actingAs($user)->put("/projects/{$otherProject->id}", [
                'name' => 'Hacked',
            ]);

            $response->assertForbidden();
        });
    });

    describe('destroy', function (): void {
        it('deletes the project', function (): void {
            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create();

            $response = $this->actingAs($user)->delete("/projects/{$project->id}");

            $response->assertRedirect('/projects');
            $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        });

        it('prevents deleting another user\'s project', function (): void {
            $user = User::factory()->create();
            $otherProject = Project::factory()->create();

            $response = $this->actingAs($user)->delete("/projects/{$otherProject->id}");

            $response->assertForbidden();
            $this->assertDatabaseHas('projects', ['id' => $otherProject->id]);
        });
    });
});
