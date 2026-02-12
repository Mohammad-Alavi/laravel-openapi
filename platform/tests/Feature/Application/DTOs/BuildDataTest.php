<?php

declare(strict_types=1);

use App\Application\DTOs\BuildData;
use App\Enums\BuildStatus;
use App\Models\Build;
use App\Models\Project;

describe('BuildData', function (): void {
    it('maps all fields from Build model', function (): void {
        $project = Project::factory()->create();
        $build = Build::factory()->for($project)->create([
            'commit_sha' => 'abc123def456',
            'status' => BuildStatus::Completed,
            'error_log' => null,
            'started_at' => now()->subMinutes(5),
            'completed_at' => now(),
        ]);

        $data = BuildData::fromModel($build);

        expect($data->id)->toBe($build->ulid)
            ->and($data->commit_sha)->toBe('abc123def456')
            ->and($data->status)->toBe('completed')
            ->and($data->error_log)->toBeNull()
            ->and($data->started_at)->not->toBeNull()
            ->and($data->completed_at)->not->toBeNull();
    });

    it('uses ULID as id, not integer primary key', function (): void {
        $build = Build::factory()->create();

        $data = BuildData::fromModel($build);

        expect($data->id)->toBe($build->ulid)
            ->and($data->id)->not->toBe((string) $build->id);
    });

    it('formats timestamps as ISO8601 strings', function (): void {
        $build = Build::factory()->create([
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        $data = BuildData::fromModel($build);

        expect($data->started_at)->toContain('T')
            ->and($data->completed_at)->toContain('T');
    });

    it('handles null timestamps', function (): void {
        $build = Build::factory()->create([
            'started_at' => null,
            'completed_at' => null,
        ]);

        $data = BuildData::fromModel($build);

        expect($data->started_at)->toBeNull()
            ->and($data->completed_at)->toBeNull();
    });

    it('includes error log when present', function (): void {
        $build = Build::factory()->create([
            'status' => BuildStatus::Failed,
            'error_log' => 'Something went wrong',
        ]);

        $data = BuildData::fromModel($build);

        expect($data->error_log)->toBe('Something went wrong');
    });

    it('does not expose internal model fields', function (): void {
        $build = Build::factory()->create();

        $data = BuildData::fromModel($build);
        $array = $data->toArray();

        expect($array)->not->toHaveKey('project_id')
            ->and($array)->not->toHaveKey('output_path')
            ->and($array)->toHaveKeys(['id', 'commit_sha', 'status', 'error_log', 'started_at', 'completed_at']);
    });
});
