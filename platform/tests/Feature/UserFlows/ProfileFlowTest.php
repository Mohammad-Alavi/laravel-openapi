<?php

declare(strict_types=1);

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Http;

describe('Profile Flow', function (): void {
    it('shows profile with all user data', function (): void {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'github_avatar' => 'https://avatars.githubusercontent.com/u/123',
        ]);

        $this->actingAs($user)->get('/profile')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Profile/Show')
                ->where('auth.user.name', 'Test User')
                ->where('auth.user.email', 'test@example.com')
                ->where('auth.user.github_avatar', 'https://avatars.githubusercontent.com/u/123')
                ->missing('auth.user.github_token')
            );
    });

    it('syncs profile from GitHub and shows updated data', function (): void {
        Http::fake([
            'api.github.com/user' => Http::response([
                'name' => 'Updated Name',
                'email' => 'new@example.com',
                'avatar_url' => 'https://avatars.githubusercontent.com/u/456',
            ]),
        ]);

        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        // Sync
        $this->actingAs($user)->post('/profile/sync')
            ->assertRedirect();

        // Verify updated on profile page
        $this->actingAs($user)->get('/profile')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('auth.user.name', 'Updated Name')
                ->where('auth.user.email', 'new@example.com')
            );
    });

    it('deletes account and cascades to all projects', function (): void {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $this->actingAs($user)->delete('/profile')
            ->assertRedirect('/');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    });
});
