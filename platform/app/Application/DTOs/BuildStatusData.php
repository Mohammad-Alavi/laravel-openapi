<?php

declare(strict_types=1);

namespace App\Application\DTOs;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Spatie\LaravelData\Data;

final class BuildStatusData extends Data
{
    public function __construct(
        public string $status,
        public ?string $last_built_at,
        /** @var array{commit_sha: string, status: string}|null */
        public ?array $latest_build,
    ) {}

    public static function fromProject(Project $project): self
    {
        $latestBuild = null;

        if ($project->status === ProjectStatus::Building) {
            $build = $project->builds()->latest()->first();

            if ($build !== null) {
                $latestBuild = [
                    'commit_sha' => $build->commit_sha,
                    'status' => $build->status->value,
                ];
            }
        }

        return new self(
            status: $project->status->value,
            last_built_at: $project->last_built_at?->toJSON(),
            latest_build: $latestBuild,
        );
    }
}
