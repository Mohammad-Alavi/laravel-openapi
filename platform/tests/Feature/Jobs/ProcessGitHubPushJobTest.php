<?php

use App\Enums\BuildStatus;
use App\Enums\ProjectStatus;
use App\Jobs\ProcessGitHubPushJob;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Process;

describe('ProcessGitHubPushJob', function (): void {
    it('sets project status to building then back to active', function (): void {
        Process::fake();
        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Active,
        ]);

        $job = new ProcessGitHubPushJob($project, 'abc123');
        $job->handle(app(\App\Services\BuildRunner::class));

        expect($project->fresh()->status)->toBe(ProjectStatus::Active);
    });

    it('updates last_built_at timestamp', function (): void {
        Process::fake();
        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create([
            'last_built_at' => null,
        ]);

        $job = new ProcessGitHubPushJob($project, 'abc123');
        $job->handle(app(\App\Services\BuildRunner::class));

        expect($project->fresh()->last_built_at)->not->toBeNull();
    });

    it('implements ShouldQueue', function (): void {
        expect(new ProcessGitHubPushJob(
            Project::factory()->makeOne(),
            'abc123',
        ))->toBeInstanceOf(ShouldQueue::class);
    });

    it('stores the commit SHA', function (): void {
        $job = new ProcessGitHubPushJob(
            Project::factory()->makeOne(),
            'abc123def456',
        );

        expect($job->commitSha)->toBe('abc123def456');
    });

    it('creates a build record for the project', function (): void {
        Process::fake();
        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create();

        $job = new ProcessGitHubPushJob($project, 'abc123def456');
        $job->handle(app(\App\Services\BuildRunner::class));

        $build = Build::where('project_id', $project->id)->first();

        expect($build)->not->toBeNull()
            ->and($build->commit_sha)->toBe('abc123def456');
    });

    it('restores project to active even when build fails', function (): void {
        Process::fake([
            'git clone *' => Process::result(exitCode: 128, errorOutput: 'clone failed'),
            '*' => Process::result(exitCode: 0),
        ]);
        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Active,
        ]);

        $job = new ProcessGitHubPushJob($project, 'abc123');
        $job->handle(app(\App\Services\BuildRunner::class));

        $project->refresh();

        expect($project->status)->toBe(ProjectStatus::Active)
            ->and($project->last_built_at)->not->toBeNull();
    });

    it('delegates to BuildRunner service', function (): void {
        Process::fake();
        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create([
            'github_repo_url' => 'https://github.com/user/repo',
            'github_branch' => 'main',
        ]);

        $job = new ProcessGitHubPushJob($project, 'abc123');
        $job->handle(app(\App\Services\BuildRunner::class));

        Process::assertRan(function ($process): bool {
            return str_contains($process->command, 'git clone --depth=1 --branch=main')
                && str_contains($process->command, 'github.com/user/repo.git');
        });
    });
});
