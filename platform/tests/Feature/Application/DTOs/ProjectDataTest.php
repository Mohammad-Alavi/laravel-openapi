<?php

use App\Application\DTOs\ProjectData;
use App\Enums\ProjectStatus;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;

describe(class_basename(ProjectData::class), function (): void {
    it('maps all fields from Project model', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'name' => 'My API',
            'description' => 'A great API',
            'github_repo_url' => 'https://github.com/user/repo',
            'github_branch' => 'develop',
            'status' => ProjectStatus::Active,
        ]);

        $dto = ProjectData::fromModel($project);

        expect($dto->id)->toBe($project->ulid)
            ->and($dto->name)->toBe('My API')
            ->and($dto->slug)->toBe($project->slug)
            ->and($dto->description)->toBe('A great API')
            ->and($dto->github_repo_url)->toBe('https://github.com/user/repo')
            ->and($dto->github_branch)->toBe('develop')
            ->and($dto->status)->toBe('active')
            ->and($dto->created_at)->toBe($project->created_at->toJSON())
            ->and($dto->updated_at)->toBe($project->updated_at->toJSON());
    });

    it('uses ULID as id, not integer primary key', function (): void {
        $project = Project::factory()->create();

        $dto = ProjectData::fromModel($project);

        expect($dto->id)->toBe($project->ulid)
            ->and($dto->id)->not->toBe($project->id)
            ->and($dto->id)->toBeString();
    });

    it('sets has_builds to true when latest_build_id is present', function (): void {
        $project = Project::factory()->create();
        $build = Build::factory()->for($project)->create();
        $project->update(['latest_build_id' => $build->id]);

        $dto = ProjectData::fromModel($project->fresh());

        expect($dto->has_builds)->toBeTrue();
    });

    it('sets has_builds to false when latest_build_id is null', function (): void {
        $project = Project::factory()->create(['latest_build_id' => null]);

        $dto = ProjectData::fromModel($project);

        expect($dto->has_builds)->toBeFalse();
    });

    it('formats last_built_at as ISO8601 string', function (): void {
        $project = Project::factory()->create([
            'last_built_at' => '2025-06-01 12:00:00',
        ]);

        $dto = ProjectData::fromModel($project);

        expect($dto->last_built_at)->toBe($project->last_built_at->toJSON());
    });

    it('handles null last_built_at', function (): void {
        $project = Project::factory()->create(['last_built_at' => null]);

        $dto = ProjectData::fromModel($project);

        expect($dto->last_built_at)->toBeNull();
    });

    it('does not expose webhook secrets or internal IDs', function (): void {
        $project = Project::factory()->create([
            'github_webhook_id' => 42,
            'github_webhook_secret' => 'super-secret',
        ]);

        $json = json_encode(ProjectData::fromModel($project));

        expect($json)->not->toContain('github_webhook_id')
            ->and($json)->not->toContain('github_webhook_secret')
            ->and($json)->not->toContain('super-secret')
            ->and($json)->not->toContain('user_id')
            ->and($json)->not->toContain('latest_build_id');
    });
})->covers(ProjectData::class);
