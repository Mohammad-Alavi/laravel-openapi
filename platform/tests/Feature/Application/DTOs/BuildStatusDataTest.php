<?php

declare(strict_types=1);

use App\Application\DTOs\BuildStatusData;
use App\Enums\BuildStatus;
use App\Enums\ProjectStatus;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;

describe(class_basename(BuildStatusData::class), function (): void {
    it('maps status and last_built_at from project', function (): void {
        $project = Project::factory()->create([
            'status' => ProjectStatus::Active,
            'last_built_at' => '2025-06-01 12:00:00',
        ]);

        $dto = BuildStatusData::fromProject($project);

        expect($dto->status)->toBe('active')
            ->and($dto->last_built_at)->toBe($project->last_built_at->toJSON())
            ->and($dto->latest_build)->toBeNull();
    });

    it('includes latest build when project is building', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Building,
        ]);
        Build::factory()->for($project)->create([
            'commit_sha' => 'abc123',
            'status' => BuildStatus::Building,
        ]);

        $dto = BuildStatusData::fromProject($project);

        expect($dto->status)->toBe('building')
            ->and($dto->latest_build)->toBe([
                'commit_sha' => 'abc123',
                'status' => 'building',
            ]);
    });

    it('does not include latest build when project is not building', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Active,
        ]);
        Build::factory()->for($project)->create();

        $dto = BuildStatusData::fromProject($project);

        expect($dto->latest_build)->toBeNull();
    });

    it('handles building project with no builds yet', function (): void {
        $project = Project::factory()->create([
            'status' => ProjectStatus::Building,
        ]);

        $dto = BuildStatusData::fromProject($project);

        expect($dto->status)->toBe('building')
            ->and($dto->latest_build)->toBeNull();
    });

    it('handles null last_built_at', function (): void {
        $project = Project::factory()->create([
            'last_built_at' => null,
        ]);

        $dto = BuildStatusData::fromProject($project);

        expect($dto->last_built_at)->toBeNull();
    });
})->covers(BuildStatusData::class);
