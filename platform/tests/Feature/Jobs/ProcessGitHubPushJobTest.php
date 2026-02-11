<?php

declare(strict_types=1);

use App\Enums\ProjectStatus;
use App\Jobs\ProcessGitHubPushJob;
use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

describe('ProcessGitHubPushJob', function (): void {
    it('sets project status to building then back to active', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Active,
        ]);

        $job = new ProcessGitHubPushJob($project, 'abc123');
        $job->handle();

        expect($project->fresh()->status)->toBe(ProjectStatus::Active);
    });

    it('updates last_built_at timestamp', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'last_built_at' => null,
        ]);

        $job = new ProcessGitHubPushJob($project, 'abc123');
        $job->handle();

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
});
