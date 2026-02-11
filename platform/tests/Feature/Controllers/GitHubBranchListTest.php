<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Http;

describe('GitHubRepoController@branches', function (): void {
    it('returns branches for a repository', function (): void {
        Http::fake([
            'api.github.com/repos/user/repo/branches*' => Http::response([
                ['name' => 'main', 'commit' => ['sha' => 'abc123']],
                ['name' => 'develop', 'commit' => ['sha' => 'def456']],
                ['name' => 'feature/auth', 'commit' => ['sha' => 'ghi789']],
            ]),
        ]);

        $user = User::factory()->create(['github_token' => 'test-token']);

        $response = $this->actingAs($user)
            ->getJson('/github/branches?repo=user/repo');

        $response->assertOk()
            ->assertExactJson(['main', 'develop', 'feature/auth']);
    });

    it('validates repo parameter is required', function (): void {
        $user = User::factory()->create(['github_token' => 'test-token']);

        $this->actingAs($user)
            ->getJson('/github/branches')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('repo');
    });

    it('validates repo parameter format as owner/repo', function (): void {
        $user = User::factory()->create(['github_token' => 'test-token']);

        $this->actingAs($user)
            ->getJson('/github/branches?repo=invalid-format')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('repo');
    });

    it('returns empty array on GitHub API failure', function (): void {
        Http::fake([
            'api.github.com/repos/user/repo/branches*' => Http::response(['message' => 'Not Found'], 404),
        ]);

        $user = User::factory()->create(['github_token' => 'test-token']);

        $response = $this->actingAs($user)
            ->getJson('/github/branches?repo=user/repo');

        $response->assertOk()
            ->assertExactJson([]);
    });

    it('requires authentication', function (): void {
        $this->getJson('/github/branches?repo=user/repo')
            ->assertUnauthorized();
    });

    it('returns only branch names without extra data', function (): void {
        Http::fake([
            'api.github.com/repos/owner/project/branches*' => Http::response([
                [
                    'name' => 'main',
                    'commit' => ['sha' => 'abc123', 'url' => 'https://api.github.com/...'],
                    'protected' => true,
                ],
            ]),
        ]);

        $user = User::factory()->create(['github_token' => 'test-token']);

        $response = $this->actingAs($user)
            ->getJson('/github/branches?repo=owner/project');

        $response->assertOk()
            ->assertExactJson(['main']);
    });
});
