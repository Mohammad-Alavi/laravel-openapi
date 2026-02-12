<?php

use App\Enums\BuildStatus;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;
use Carbon\CarbonImmutable;

describe('Build', function (): void {
    it('belongs to a project', function (): void {
        $project = Project::factory()->for(User::factory())->create();
        $build = Build::factory()->for($project)->create();

        expect($build->project->id)->toBe($project->id);
    });

    it('has pending status by default', function (): void {
        $build = Build::factory()->create();

        expect($build->status)->toBe(BuildStatus::Pending);
    });

    it('casts status to BuildStatus enum', function (): void {
        $build = Build::factory()->create(['status' => BuildStatus::Completed]);

        expect($build->fresh()->status)->toBe(BuildStatus::Completed)
            ->and($build->status)->toBeInstanceOf(BuildStatus::class);
    });

    it('casts started_at and completed_at to datetime', function (): void {
        $build = Build::factory()->create([
            'started_at' => '2024-01-15 10:00:00',
            'completed_at' => '2024-01-15 10:05:00',
        ]);

        $build = $build->fresh();

        expect($build->started_at)->toBeInstanceOf(CarbonImmutable::class)
            ->and($build->completed_at)->toBeInstanceOf(CarbonImmutable::class);
    });

    it('is cascade deleted when project is deleted', function (): void {
        $project = Project::factory()->for(User::factory())->create();
        $build = Build::factory()->for($project)->create();
        $buildId = $build->id;

        $project->delete();

        expect(Build::find($buildId))->toBeNull();
    });
});
