<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Project> */
final class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'description' => fake()->sentence(),
            'github_repo_url' => 'https://github.com/' . fake()->userName() . '/' . fake()->slug(2),
            'github_branch' => 'main',
            'status' => ProjectStatus::Active,
        ];
    }
}
