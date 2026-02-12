<?php

declare(strict_types=1);

use App\Enums\BuildStatus;
use App\Enums\ProjectStatus;
use App\Jobs\ProcessGitHubPushJob;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

describe('Rebuild Flow', function (): void {
    it('triggers rebuild and transitions project through building state', function (): void {
        Queue::fake();
        Http::fake([
            'api.github.com/repos/user/repo/commits/develop' => Http::response([
                'sha' => 'abc123rebuild',
            ]),
        ]);

        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'github_repo_url' => 'https://github.com/user/repo',
            'github_branch' => 'develop',
            'status' => ProjectStatus::Active,
        ]);

        // Step 1: View project — active status, rebuild available
        $this->actingAs($user)->get("/projects/{$project->slug}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('project.status', 'active')
            );

        // Step 2: Trigger rebuild
        $this->actingAs($user)->post("/projects/{$project->slug}/rebuild")
            ->assertRedirect();

        Queue::assertPushed(ProcessGitHubPushJob::class);

        // Step 3: Project is now building
        expect($project->fresh()->status)->toBe(ProjectStatus::Building);

        // Step 4: Check status endpoint returns building
        $this->actingAs($user)->getJson("/projects/{$project->slug}/status")
            ->assertOk()
            ->assertJsonPath('status', 'building');
    });

    it('prevents rebuild when already building', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Building,
        ]);

        $this->actingAs($user)->post("/projects/{$project->slug}/rebuild")
            ->assertRedirect()
            ->assertSessionHas('error', 'A build is already in progress.');
    });

    it('prevents non-owner from triggering rebuild', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $this->actingAs($user)->post("/projects/{$project->slug}/rebuild")
            ->assertForbidden();
    });
});
