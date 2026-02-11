<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Build;
use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

final class CleanupBuildsCommand extends Command
{
    protected $signature = 'builds:cleanup';

    protected $description = 'Clean up old builds based on retention policy (keep last 10 or last 30 days)';

    public function handle(): int
    {
        $totalDeleted = 0;

        Project::query()->each(function (Project $project) use (&$totalDeleted): void {
            $recentIds = $project->builds()
                ->latest()
                ->take(10)
                ->pluck('id');

            $withinRetentionIds = $project->builds()
                ->where('created_at', '>=', now()->subDays(30))
                ->pluck('id');

            $keepIds = $recentIds->merge($withinRetentionIds)->unique();

            $buildsToDelete = $project->builds()
                ->whereNotIn('id', $keepIds)
                ->get();

            foreach ($buildsToDelete as $build) {
                if ($build->output_path !== null) {
                    Storage::delete($build->output_path);
                }

                $build->delete();
                $totalDeleted++;
            }
        });

        $this->info("Cleaned up {$totalDeleted} build(s).");

        return self::SUCCESS;
    }
}
