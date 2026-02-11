<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Http;

describe('GitHubRepoController@repos', function (): void {
    it('returns repos matching search query', function (): void {
        Http::fake([
            'api.github.com/user/repos*' => Http::response([
                [
                    'id' => 1,
                    'full_name' => 'user/laravel-app',
                    'name' => 'laravel-app',
                    'description' => 'A Laravel application',
                    'html_url' => 'https://github.com/user/laravel-app',
                    'default_branch' => 'main',
                    'private' => false,
                    'owner' => ['id' => 99, 'login' => 'user'],
                    'permissions' => ['admin' => true],
                ],
                [
                    'id' => 2,
                    'full_name' => 'user/vue-project',
                    'name' => 'vue-project',
                    'description' => 'A Vue project',
                    'html_url' => 'https://github.com/user/vue-project',
                    'default_branch' => 'develop',
                    'private' => true,
                    'owner' => ['id' => 99, 'login' => 'user'],
                    'permissions' => ['admin' => true],
                ],
            ]),
        ]);

        $user = User::factory()->create(['github_token' => 'test-token']);

        $response = $this->actingAs($user)
            ->getJson('/github/repos?q=laravel');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['full_name' => 'user/laravel-app']);
    });

    it('returns all repos when no query provided', function (): void {
        Http::fake([
            'api.github.com/user/repos*' => Http::response([
                [
                    'id' => 1,
                    'full_name' => 'user/repo-one',
                    'name' => 'repo-one',
                    'description' => null,
                    'html_url' => 'https://github.com/user/repo-one',
                    'default_branch' => 'main',
                    'private' => false,
                    'owner' => ['id' => 99],
                    'permissions' => ['admin' => true],
                ],
                [
                    'id' => 2,
                    'full_name' => 'user/repo-two',
                    'name' => 'repo-two',
                    'description' => 'Second repo',
                    'html_url' => 'https://github.com/user/repo-two',
                    'default_branch' => 'develop',
                    'private' => true,
                    'owner' => ['id' => 99],
                    'permissions' => ['admin' => true],
                ],
            ]),
        ]);

        $user = User::factory()->create(['github_token' => 'test-token']);

        $response = $this->actingAs($user)
            ->getJson('/github/repos');

        $response->assertOk()
            ->assertJsonCount(2);
    });

    it('returns empty array on GitHub API failure', function (): void {
        Http::fake([
            'api.github.com/user/repos*' => Http::response(['message' => 'Bad credentials'], 401),
        ]);

        $user = User::factory()->create(['github_token' => 'invalid-token']);

        $response = $this->actingAs($user)
            ->getJson('/github/repos?q=test');

        $response->assertOk()
            ->assertExactJson([]);
    });

    it('requires authentication', function (): void {
        $this->getJson('/github/repos?q=test')
            ->assertUnauthorized();
    });

    it('does not leak extra GitHub fields', function (): void {
        Http::fake([
            'api.github.com/user/repos*' => Http::response([
                [
                    'id' => 1,
                    'full_name' => 'user/repo',
                    'name' => 'repo',
                    'description' => 'A repo',
                    'html_url' => 'https://github.com/user/repo',
                    'default_branch' => 'main',
                    'private' => false,
                    'owner' => ['id' => 99, 'login' => 'user'],
                    'permissions' => ['admin' => true, 'push' => true],
                    'fork' => false,
                    'size' => 1024,
                ],
            ]),
        ]);

        $user = User::factory()->create(['github_token' => 'test-token']);

        $response = $this->actingAs($user)
            ->getJson('/github/repos');

        $response->assertOk();
        $repo = $response->json()[0];
        expect($repo)->toHaveKeys(['full_name', 'name', 'description', 'url', 'default_branch', 'private'])
            ->and($repo)->not->toHaveKeys(['id', 'owner', 'permissions', 'fork', 'size']);
    });

    it('limits results to 25 repos', function (): void {
        $repos = collect(range(1, 30))->map(fn (int $i) => [
            'id' => $i,
            'full_name' => "user/repo-{$i}",
            'name' => "repo-{$i}",
            'description' => null,
            'html_url' => "https://github.com/user/repo-{$i}",
            'default_branch' => 'main',
            'private' => false,
            'owner' => ['id' => 99],
            'permissions' => ['admin' => true],
        ])->all();

        Http::fake([
            'api.github.com/user/repos*' => Http::response($repos),
        ]);

        $user = User::factory()->create(['github_token' => 'test-token']);

        $response = $this->actingAs($user)
            ->getJson('/github/repos');

        $response->assertOk()
            ->assertJsonCount(25);
    });

    it('maps html_url to url in response', function (): void {
        Http::fake([
            'api.github.com/user/repos*' => Http::response([
                [
                    'id' => 1,
                    'full_name' => 'user/repo',
                    'name' => 'repo',
                    'description' => 'A repo',
                    'html_url' => 'https://github.com/user/repo',
                    'default_branch' => 'main',
                    'private' => false,
                    'owner' => ['id' => 99],
                    'permissions' => ['admin' => true],
                ],
            ]),
        ]);

        $user = User::factory()->create(['github_token' => 'test-token']);

        $response = $this->actingAs($user)
            ->getJson('/github/repos');

        $response->assertOk()
            ->assertJsonFragment(['url' => 'https://github.com/user/repo']);
    });
});
