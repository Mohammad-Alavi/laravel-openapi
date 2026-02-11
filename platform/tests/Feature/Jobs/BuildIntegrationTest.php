<?php

declare(strict_types=1);

use App\Enums\BuildStatus;
use App\Enums\ProjectStatus;
use App\Jobs\ProcessGitHubPushJob;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Process;

describe('Build Integration', function (): void {
    it('creates build and updates project on successful build', function (): void {
        Process::fake();

        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Active,
            'last_built_at' => null,
        ]);

        ProcessGitHubPushJob::dispatchSync($project, 'abc123def456');

        $project->refresh();
        $build = Build::where('project_id', $project->id)->first();

        expect($project->status)->toBe(ProjectStatus::Active)
            ->and($project->last_built_at)->not->toBeNull()
            ->and($build)->not->toBeNull()
            ->and($build->status)->toBe(BuildStatus::Completed)
            ->and($build->commit_sha)->toBe('abc123def456')
            ->and($build->started_at)->not->toBeNull()
            ->and($build->completed_at)->not->toBeNull();
    });

    it('handles failure gracefully', function (): void {
        Process::fake([
            'git clone *' => Process::result(exitCode: 128, errorOutput: 'fatal: repository not found'),
            '*' => Process::result(exitCode: 0),
        ]);

        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Active,
        ]);

        ProcessGitHubPushJob::dispatchSync($project, 'abc123');

        $project->refresh();
        $build = Build::where('project_id', $project->id)->first();

        expect($project->status)->toBe(ProjectStatus::Active)
            ->and($build->status)->toBe(BuildStatus::Failed)
            ->and($build->error_log)->toContain('repository not found');
    });
});
