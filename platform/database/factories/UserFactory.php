<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<User> */
final class UserFactory extends Factory
{
    protected $model = User::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'github_id' => (string) fake()->unique()->randomNumber(8),
            'github_token' => fake()->sha256(),
            'github_avatar' => fake()->imageUrl(),
        ];
    }
}
