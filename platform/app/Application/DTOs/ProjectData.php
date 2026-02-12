<?php

namespace App\Application\DTOs;

use App\Models\Project;
use Spatie\LaravelData\Data;

final class ProjectData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug,
        public ?string $description,
        public string $github_repo_url,
        public string $github_branch,
        public string $status,
        public ?string $last_built_at,
        public bool $has_builds,
        public string $created_at,
        public string $updated_at,
    ) {}

    public static function fromModel(Project $project): self
    {
        return new self(
            id: $project->ulid,
            name: $project->name,
            slug: $project->slug,
            description: $project->description,
            github_repo_url: $project->github_repo_url,
            github_branch: $project->github_branch,
            status: $project->status->value,
            last_built_at: $project->last_built_at?->toJSON(),
            has_builds: $project->latest_build_id !== null,
            created_at: $project->created_at->toJSON(),
            updated_at: $project->updated_at->toJSON(),
        );
    }
}
