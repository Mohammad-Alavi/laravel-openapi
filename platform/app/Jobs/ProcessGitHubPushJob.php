<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

final class ProcessGitHubPushJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public function __construct(
        public readonly Project $project,
        public readonly string $commitSha,
    ) {}

    public function handle(): void
    {
        $this->project->update(['status' => ProjectStatus::Building]);

        // Placeholder: containerized analysis will replace this
        sleep(1);

        $this->project->update([
            'status' => ProjectStatus::Active,
            'last_built_at' => now(),
        ]);
    }
}
