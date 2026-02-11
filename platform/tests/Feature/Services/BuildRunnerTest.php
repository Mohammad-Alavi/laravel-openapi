<?php

declare(strict_types=1);

use App\Enums\BuildStatus;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;
use App\Services\BuildRunner;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

describe('BuildRunner', function (): void {
    it('clones the repository with depth 1 and correct branch', function (): void {
        Process::fake();
        Storage::fake();

        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create([
            'github_repo_url' => 'https://github.com/user/repo',
            'github_branch' => 'develop',
        ]);
        $build = Build::factory()->for($project)->create();

        app(BuildRunner::class)->run($build);

        Process::assertRan(function (PendingProcess $process): bool {
            return str_contains($process->command, 'git clone --depth=1 --branch=develop')
                && str_contains($process->command, 'github.com/user/repo.git');
        });
    });

    it('runs docker container with memory and cpu limits', function (): void {
        Process::fake();
        Storage::fake();

        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create();

        app(BuildRunner::class)->run($build);

        Process::assertRan(function (PendingProcess $process): bool {
            return str_contains($process->command, 'docker run --rm')
                && str_contains($process->command, '--memory=512m')
                && str_contains($process->command, '--cpus=1')
                && str_contains($process->command, 'laragen-build-runner');
        });
    });

    it('marks build as completed when all steps succeed', function (): void {
        Process::fake();
        Storage::fake();

        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create();

        app(BuildRunner::class)->run($build);

        $build->refresh();

        expect($build->status)->toBe(BuildStatus::Completed)
            ->and($build->started_at)->not->toBeNull()
            ->and($build->completed_at)->not->toBeNull();
    });

    it('marks build as failed when clone fails', function (): void {
        Process::fake([
            'git clone *' => Process::result(exitCode: 128, errorOutput: 'fatal: repository not found'),
        ]);
        Storage::fake();

        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create();

        app(BuildRunner::class)->run($build);

        $build->refresh();

        expect($build->status)->toBe(BuildStatus::Failed)
            ->and($build->error_log)->toContain('repository not found')
            ->and($build->completed_at)->not->toBeNull();
    });

    it('marks build as failed when docker run fails', function (): void {
        Process::fake([
            'git clone *' => Process::result(exitCode: 0),
            'docker run *' => Process::result(exitCode: 137, errorOutput: 'OOM killed'),
        ]);
        Storage::fake();

        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create();

        app(BuildRunner::class)->run($build);

        $build->refresh();

        expect($build->status)->toBe(BuildStatus::Failed)
            ->and($build->error_log)->toContain('OOM killed')
            ->and($build->completed_at)->not->toBeNull();
    });

    it('cleans up workspace after build regardless of outcome', function (): void {
        Process::fake([
            'git clone *' => Process::result(exitCode: 128, errorOutput: 'clone failed'),
            '*' => Process::result(exitCode: 0),
        ]);
        Storage::fake();

        $user = User::factory()->create(['github_token' => 'test-token']);
        $project = Project::factory()->for($user)->create();
        $build = Build::factory()->for($project)->create();

        app(BuildRunner::class)->run($build);

        Process::assertRan(function (PendingProcess $process): bool {
            return str_contains($process->command, 'rm -rf');
        });
    });
});
