<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Http;

describe('Profile', function (): void {
    it('displays the profile page for authenticated users', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Profile/Show')
                ->where('user.id', $user->ulid)
                ->where('user.name', $user->name)
                ->where('user.email', $user->email)
                ->missing('user.github_token')
                ->missing('user.remember_token')
            );
    });

    it('shares auth.user as a DTO with ULID id and no sensitive fields', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('auth.user.id', $user->ulid)
                ->where('auth.user.name', $user->name)
                ->where('auth.user.email', $user->email)
                ->missing('auth.user.github_token')
                ->missing('auth.user.remember_token')
            );
    });

    it('requires authentication', function (): void {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    });

    it('re-syncs GitHub data from the API', function (): void {
        Http::fake([
            'api.github.com/user' => Http::response([
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'avatar_url' => 'https://avatars.githubusercontent.com/u/12345',
            ], 200),
        ]);

        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'github_token' => 'fake-token',
        ]);

        $response = $this->actingAs($user)->post('/profile/sync');

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success', 'GitHub profile synced successfully.');

        expect($user->fresh())
            ->name->toBe('Updated Name')
            ->and($user->fresh())->email->toBe('updated@example.com')
            ->and($user->fresh())->github_avatar->toBe('https://avatars.githubusercontent.com/u/12345');
    });

    it('shows error when GitHub sync fails', function (): void {
        Http::fake([
            'api.github.com/user' => Http::response(['message' => 'Unauthorized'], 401),
        ]);

        $user = User::factory()->create(['github_token' => 'bad-token']);

        $response = $this->actingAs($user)->post('/profile/sync');

        $response->assertRedirect('/profile');
        $response->assertSessionHas('error', 'Failed to sync GitHub profile. Please try re-authenticating.');
    });

    it('deletes the user account and all projects', function (): void {
        $user = User::factory()->create();
        $projects = Project::factory()->for($user)->count(3)->create();

        $response = $this->actingAs($user)->delete('/profile');

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        foreach ($projects as $project) {
            $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        }
    });
});
