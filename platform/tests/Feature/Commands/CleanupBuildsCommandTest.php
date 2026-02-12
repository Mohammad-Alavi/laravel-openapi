<?php

use App\Enums\BuildStatus;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

describe('CleanupBuildsCommand', function (): void {
    it('keeps the 10 most recent builds regardless of age', function (): void {
        Storage::fake();
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        // Create 12 builds, all older than 30 days
        $builds = collect(range(1, 12))->map(fn ($i) => Build::factory()->for($project)->create([
            'status' => BuildStatus::Completed,
            'output_path' => "builds/{$project->id}/build-{$i}/openapi.json",
            'created_at' => now()->subDays(60 - $i), // oldest first
        ]));

        // Put fake files for all builds
        $builds->each(fn ($build) => Storage::put($build->output_path, 'content'));

        $this->artisan('builds:cleanup')->assertSuccessful();

        // The 10 most recent should be kept (builds 3-12), oldest 2 deleted (builds 1-2)
        expect(Build::where('project_id', $project->id)->count())->toBe(10)
            ->and(Build::find($builds[0]->id))->toBeNull()
            ->and(Build::find($builds[1]->id))->toBeNull()
            ->and(Build::find($builds[11]->id))->not->toBeNull();
    });

    it('keeps builds less than 30 days old regardless of count', function (): void {
        Storage::fake();
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        // Create 15 builds, all within 30 days
        collect(range(1, 15))->each(fn ($i) => Build::factory()->for($project)->create([
            'status' => BuildStatus::Completed,
            'created_at' => now()->subDays($i),
        ]));

        $this->artisan('builds:cleanup')->assertSuccessful();

        expect(Build::where('project_id', $project->id)->count())->toBe(15);
    });

    it('deletes builds older than 30 days AND beyond the 10 most recent', function (): void {
        Storage::fake();
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        // 8 recent builds (within 30 days)
        collect(range(1, 8))->each(fn ($i) => Build::factory()->for($project)->create([
            'status' => BuildStatus::Completed,
            'created_at' => now()->subDays($i),
        ]));

        // 5 old builds (older than 30 days)
        $oldBuilds = collect(range(1, 5))->map(fn ($i) => Build::factory()->for($project)->create([
            'status' => BuildStatus::Completed,
            'output_path' => "builds/{$project->id}/old-{$i}/openapi.json",
            'created_at' => now()->subDays(40 + $i),
        ]));

        $oldBuilds->each(fn ($build) => Storage::put($build->output_path, 'content'));

        $this->artisan('builds:cleanup')->assertSuccessful();

        // 8 recent kept (< 30 days), top 10 kept = 10 total kept, 3 old ones deleted
        // Union: 8 recent + top 10 (which includes the 8 recent + 2 oldest of old) = 10 total
        // Deleted: 3 oldest old builds
        expect(Build::where('project_id', $project->id)->count())->toBe(10);
    });

    it('deletes associated storage files for removed builds', function (): void {
        Storage::fake();
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        // Create 12 old builds with output files
        $builds = collect(range(1, 12))->map(fn ($i) => Build::factory()->for($project)->create([
            'status' => BuildStatus::Completed,
            'output_path' => "builds/{$project->id}/build-{$i}/openapi.json",
            'created_at' => now()->subDays(60 - $i),
        ]));

        $builds->each(fn ($build) => Storage::put($build->output_path, 'content'));

        $this->artisan('builds:cleanup')->assertSuccessful();

        // Oldest 2 builds' files should be deleted
        Storage::assertMissing($builds[0]->output_path)
            ->assertMissing($builds[1]->output_path);

        // Most recent build's file should still exist
        Storage::assertExists($builds[11]->output_path);
    });

    it('handles builds with null output_path gracefully', function (): void {
        Storage::fake();
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        // Create 12 old builds, some without output_path
        collect(range(1, 12))->each(fn ($i) => Build::factory()->for($project)->create([
            'status' => BuildStatus::Completed,
            'output_path' => null,
            'created_at' => now()->subDays(60 - $i),
        ]));

        $this->artisan('builds:cleanup')->assertSuccessful();

        expect(Build::where('project_id', $project->id)->count())->toBe(10);
    });

    it('processes each project independently', function (): void {
        Storage::fake();
        $user = User::factory()->create();
        $projectA = Project::factory()->for($user)->create();
        $projectB = Project::factory()->for($user)->create();

        // Project A: 12 old builds — 2 should be deleted
        collect(range(1, 12))->each(fn ($i) => Build::factory()->for($projectA)->create([
            'status' => BuildStatus::Completed,
            'created_at' => now()->subDays(60 - $i),
        ]));

        // Project B: 3 old builds — none should be deleted (under 10)
        collect(range(1, 3))->each(fn ($i) => Build::factory()->for($projectB)->create([
            'status' => BuildStatus::Completed,
            'created_at' => now()->subDays(60 - $i),
        ]));

        $this->artisan('builds:cleanup')->assertSuccessful();

        expect(Build::where('project_id', $projectA->id)->count())->toBe(10)
            ->and(Build::where('project_id', $projectB->id)->count())->toBe(3);
    });

    it('does nothing when all builds are within retention', function (): void {
        Storage::fake();
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        // Create 5 recent builds — all within retention
        collect(range(1, 5))->each(fn ($i) => Build::factory()->for($project)->create([
            'status' => BuildStatus::Completed,
            'created_at' => now()->subDays($i),
        ]));

        $this->artisan('builds:cleanup')
            ->assertSuccessful()
            ->expectsOutputToContain('Cleaned up 0 build(s).');

        expect(Build::where('project_id', $project->id)->count())->toBe(5);
    });
});
