<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\BuildStatus;
use App\Enums\ProjectStatus;
use App\Events\BuildStatusChanged;
use App\Models\Build;
use App\Models\Project;
use App\Notifications\BuildCompletedNotification;
use App\Services\BuildRunner;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

final class ProcessGitHubPushJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public int $timeout = 600;

    public int $tries = 1;

    public function __construct(
        public readonly Project $project,
        public readonly string $commitSha,
    ) {}

    public function handle(BuildRunner $buildRunner): void
    {
        $this->project->update(['status' => ProjectStatus::Building]);

        $build = $this->project->builds()->create([
            'commit_sha' => $this->commitSha,
        ]);
        $build->refresh();

        BuildStatusChanged::dispatch($build);

        try {
            $buildRunner->run($build);
        } finally {
            $build->refresh();

            $updateData = [
                'status' => ProjectStatus::Active,
                'last_built_at' => now(),
            ];

            if ($build->status === BuildStatus::Completed) {
                $updateData['latest_build_id'] = $build->id;
            }

            $this->project->update($updateData);

            BuildStatusChanged::dispatch($build);

            $this->project->user->notify(new BuildCompletedNotification($build));
        }
    }
}
