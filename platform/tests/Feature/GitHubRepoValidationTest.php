<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Http;

describe('GitHub Repo Validation', function (): void {
    it('returns success for an accessible repository', function (): void {
        Http::fake([
            'api.github.com/repos/user/repo' => Http::response([
                'full_name' => 'user/repo',
                'default_branch' => 'main',
                'private' => false,
            ], 200),
        ]);

        $user = User::factory()->create(['github_token' => 'fake-token']);

        $response = $this->actingAs($user)->postJson('/github/validate-repo', [
            'github_repo_url' => 'https://github.com/user/repo',
        ]);

        $response->assertOk()
            ->assertJson([
                'valid' => true,
                'default_branch' => 'main',
                'private' => false,
            ]);
    });

    it('returns error for a non-existent repository', function (): void {
        Http::fake([
            'api.github.com/repos/user/nonexistent' => Http::response(['message' => 'Not Found'], 404),
        ]);

        $user = User::factory()->create(['github_token' => 'fake-token']);

        $response = $this->actingAs($user)->postJson('/github/validate-repo', [
            'github_repo_url' => 'https://github.com/user/nonexistent',
        ]);

        $response->assertOk()
            ->assertJson([
                'valid' => false,
                'error' => 'Repository not found. Check the URL and your access permissions.',
            ]);
    });

    it('returns error for a private repo without access', function (): void {
        Http::fake([
            'api.github.com/repos/user/private-repo' => Http::response(['message' => 'Forbidden'], 403),
        ]);

        $user = User::factory()->create(['github_token' => 'fake-token']);

        $response = $this->actingAs($user)->postJson('/github/validate-repo', [
            'github_repo_url' => 'https://github.com/user/private-repo',
        ]);

        $response->assertOk()
            ->assertJson([
                'valid' => false,
                'error' => 'You do not have access to this repository.',
            ]);
    });

    it('validates the URL format', function (): void {
        $user = User::factory()->create(['github_token' => 'fake-token']);

        $response = $this->actingAs($user)->postJson('/github/validate-repo', [
            'github_repo_url' => 'https://not-github.com/user/repo',
        ]);

        $response->assertUnprocessable();
    });

    it('requires authentication', function (): void {
        $response = $this->postJson('/github/validate-repo', [
            'github_repo_url' => 'https://github.com/user/repo',
        ]);

        $response->assertUnauthorized();
    });

    it('validates a specific branch exists', function (): void {
        Http::fake([
            'api.github.com/repos/user/repo' => Http::response([
                'full_name' => 'user/repo',
                'default_branch' => 'main',
                'private' => false,
            ], 200),
            'api.github.com/repos/user/repo/branches/develop' => Http::response([
                'name' => 'develop',
            ], 200),
        ]);

        $user = User::factory()->create(['github_token' => 'fake-token']);

        $response = $this->actingAs($user)->postJson('/github/validate-repo', [
            'github_repo_url' => 'https://github.com/user/repo',
            'branch' => 'develop',
        ]);

        $response->assertOk()
            ->assertJson([
                'valid' => true,
                'branch_valid' => true,
            ]);
    });
});
