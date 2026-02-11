<?php

declare(strict_types=1);

use App\Enums\ProjectStatus;
use App\Jobs\ProcessGitHubPushJob;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

describe('RebuildController', function (): void {
    it('dispatches ProcessGitHubPushJob with latest commit SHA from GitHub', function (): void {
        Queue::fake();
        Http::fake([
            'api.github.com/repos/owner/repo/commits/main' => Http::response([
                'sha' => 'abc123def456789',
            ]),
        ]);

        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create([
            'github_repo_url' => 'https://github.com/owner/repo',
            'github_branch' => 'main',
            'status' => ProjectStatus::Active,
        ]);

        $this->actingAs($user)
            ->post("/projects/{$project->slug}/rebuild")
            ->assertRedirect();

        Queue::assertPushed(ProcessGitHubPushJob::class, function ($job) use ($project) {
            return $job->project->id === $project->id
                && $job->commitSha === 'abc123def456789';
        });
    });

    it('returns 409 when project is already building', function (): void {
        Queue::fake();
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Building,
        ]);

        $this->actingAs($user)
            ->post("/projects/{$project->slug}/rebuild")
            ->assertRedirect()
            ->assertSessionHas('error');

        Queue::assertNothingPushed();
    });

    it('requires authentication', function (): void {
        $project = Project::factory()->create();

        $this->post("/projects/{$project->slug}/rebuild")
            ->assertRedirect('/login');
    });

    it('returns 403 for non-owner', function (): void {
        $user = User::factory()->create();
        $otherProject = Project::factory()->create();

        $this->actingAs($user)
            ->post("/projects/{$otherProject->slug}/rebuild")
            ->assertForbidden();
    });

    it('fetches latest commit from the configured branch', function (): void {
        Queue::fake();
        Http::fake([
            'api.github.com/repos/owner/repo/commits/develop' => Http::response([
                'sha' => 'develop-sha-123',
            ]),
        ]);

        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create([
            'github_repo_url' => 'https://github.com/owner/repo',
            'github_branch' => 'develop',
            'status' => ProjectStatus::Active,
        ]);

        $this->actingAs($user)->post("/projects/{$project->slug}/rebuild");

        Http::assertSent(fn ($request) => str_contains($request->url(), 'api.github.com/repos/owner/repo/commits/develop'));
    });

    it('returns error when GitHub API fails', function (): void {
        Queue::fake();
        Http::fake([
            'api.github.com/*' => Http::response(['message' => 'Not Found'], 404),
        ]);

        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create([
            'github_repo_url' => 'https://github.com/owner/repo',
            'github_branch' => 'main',
            'status' => ProjectStatus::Active,
        ]);

        $this->actingAs($user)
            ->post("/projects/{$project->slug}/rebuild")
            ->assertRedirect()
            ->assertSessionHas('error');

        Queue::assertNothingPushed();
    });

    it('redirects back with success flash message', function (): void {
        Queue::fake();
        Http::fake([
            'api.github.com/repos/owner/repo/commits/main' => Http::response([
                'sha' => 'abc123',
            ]),
        ]);

        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create([
            'github_repo_url' => 'https://github.com/owner/repo',
            'github_branch' => 'main',
            'status' => ProjectStatus::Active,
        ]);

        $this->actingAs($user)
            ->post("/projects/{$project->slug}/rebuild")
            ->assertRedirect()
            ->assertSessionHas('success');
    });
});
