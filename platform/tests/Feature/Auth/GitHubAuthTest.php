<?php

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GithubProvider;
use Laravel\Socialite\Two\User as SocialiteUser;

describe('GitHub Authentication', function (): void {
    it('redirects to GitHub for authentication', function (): void {
        $response = $this->get('/auth/github/redirect');

        $response->assertRedirectContains('github.com');
    });

    it('creates a new user from GitHub callback', function (): void {
        $socialiteUser = mockSocialiteUser();

        Socialite::shouldReceive('driver')
            ->with('github')
            ->andReturn(mockGitHubProvider($socialiteUser));

        $response = $this->get('/auth/github/callback');

        $response->assertRedirect('/projects');

        $this->assertDatabaseHas('users', [
            'github_id' => '12345',
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertAuthenticated();
    });

    it('updates existing user on repeat GitHub login', function (): void {
        $user = User::factory()->create([
            'github_id' => '12345',
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $socialiteUser = mockSocialiteUser([
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        Socialite::shouldReceive('driver')
            ->with('github')
            ->andReturn(mockGitHubProvider($socialiteUser));

        $response = $this->get('/auth/github/callback');

        $response->assertRedirect('/projects');

        expect($user->fresh())
            ->name->toBe('Updated Name')
            ->and($user->fresh())->email->toBe('updated@example.com');

        $this->assertAuthenticatedAs($user);
    });

    it('redirects unauthenticated users to login', function (): void {
        $response = $this->get('/projects');

        $response->assertRedirect('/login');
    });

    it('logs out the user', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    });
});

function mockSocialiteUser(array $overrides = []): SocialiteUser
{
    $user = new SocialiteUser();
    $user->id = $overrides['github_id'] ?? '12345';
    $user->name = $overrides['name'] ?? 'Test User';
    $user->email = $overrides['email'] ?? 'test@example.com';
    $user->avatar = $overrides['avatar'] ?? 'https://avatars.githubusercontent.com/u/12345';
    $user->token = $overrides['token'] ?? 'github-token-123';

    return $user;
}

function mockGitHubProvider(SocialiteUser $user): GithubProvider
{
    $provider = Mockery::mock(GithubProvider::class);
    $provider->shouldReceive('user')->andReturn($user);

    return $provider;
}
