<?php

namespace App\Application\DTOs;

use App\Models\Build;
use Spatie\LaravelData\Data;

final class BuildData extends Data
{
    public function __construct(
        public string $id,
        public string $commit_sha,
        public string $status,
        public ?string $error_log,
        public ?string $started_at,
        public ?string $completed_at,
    ) {}

    public static function fromModel(Build $build): self
    {
        return new self(
            id: $build->ulid,
            commit_sha: $build->commit_sha,
            status: $build->status->value,
            error_log: $build->error_log,
            started_at: $build->started_at?->toJSON(),
            completed_at: $build->completed_at?->toJSON(),
        );
    }
}
