<?php

declare(strict_types=1);

use App\Enums\BuildStatus;
use App\Enums\ProjectStatus;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;

describe('ProjectStatusController', function (): void {
    it('returns project status and last_built_at as JSON', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Active,
            'last_built_at' => '2025-06-01 12:00:00',
        ]);

        $this->actingAs($user)
            ->getJson("/projects/{$project->id}/status")
            ->assertOk()
            ->assertJson([
                'status' => 'active',
                'last_built_at' => $project->last_built_at->toJSON(),
            ]);
    });

    it('requires authentication', function (): void {
        $project = Project::factory()->create();

        $this->getJson("/projects/{$project->id}/status")
            ->assertUnauthorized();
    });

    it('returns 403 for non-owner', function (): void {
        $user = User::factory()->create();
        $otherProject = Project::factory()->create();

        $this->actingAs($user)
            ->getJson("/projects/{$otherProject->id}/status")
            ->assertForbidden();
    });

    it('returns building status when project is building', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Building,
        ]);

        $this->actingAs($user)
            ->getJson("/projects/{$project->id}/status")
            ->assertOk()
            ->assertJson([
                'status' => 'building',
            ]);
    });

    it('includes latest build commit_sha when building', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'status' => ProjectStatus::Building,
        ]);
        $build = Build::factory()->for($project)->create([
            'commit_sha' => 'abc123def456',
            'status' => BuildStatus::Building,
        ]);

        $this->actingAs($user)
            ->getJson("/projects/{$project->id}/status")
            ->assertOk()
            ->assertJson([
                'status' => 'building',
                'latest_build' => [
                    'commit_sha' => 'abc123def456',
                    'status' => 'building',
                ],
            ]);
    });
});
