<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BuildStatus;
use App\Models\Build;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Build> */
final class BuildFactory extends Factory
{
    protected $model = Build::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'commit_sha' => fake()->sha1(),
            'status' => BuildStatus::Pending,
        ];
    }
}
