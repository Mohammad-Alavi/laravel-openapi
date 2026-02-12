<?php

use App\Models\Project;
use App\Models\User;
use App\Services\GitHubWebhookService;
use Illuminate\Support\Facades\Http;

describe('GitHubWebhookService', function (): void {
    describe('register', function (): void {
        it('registers a webhook and stores id + secret on the project', function (): void {
            Http::fake([
                'api.github.com/repos/user/repo/hooks' => Http::response(['id' => 12345], 201),
            ]);

            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create([
                'github_repo_url' => 'https://github.com/user/repo',
            ]);

            $service = app(GitHubWebhookService::class);
            $result = $service->register($project);

            expect($result)->toBeTrue()
                ->and($project->fresh()->github_webhook_id)->toBe(12345)
                ->and($project->fresh()->github_webhook_secret)->not->toBeNull();
        });

        it('returns false when GitHub API returns an error', function (): void {
            Http::fake([
                'api.github.com/repos/user/repo/hooks' => Http::response(['message' => 'Validation Failed'], 422),
            ]);

            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create([
                'github_repo_url' => 'https://github.com/user/repo',
            ]);

            $service = app(GitHubWebhookService::class);
            $result = $service->register($project);

            expect($result)->toBeFalse()
                ->and($project->fresh()->github_webhook_id)->toBeNull();
        });

        it('sends the correct webhook URL from config', function (): void {
            Http::fake([
                'api.github.com/repos/user/repo/hooks' => Http::response(['id' => 1], 201),
            ]);

            config(['services.github.webhook_url' => 'https://example.com/webhooks/github']);

            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create([
                'github_repo_url' => 'https://github.com/user/repo',
            ]);

            $service = app(GitHubWebhookService::class);
            $service->register($project);

            Http::assertSent(function ($request) {
                return $request['config']['url'] === 'https://example.com/webhooks/github';
            });
        });

        it('uses the project owner\'s github_token for auth', function (): void {
            Http::fake([
                'api.github.com/repos/user/repo/hooks' => Http::response(['id' => 1], 201),
            ]);

            $user = User::factory()->create(['github_token' => 'test-token-123']);
            $project = Project::factory()->for($user)->create([
                'github_repo_url' => 'https://github.com/user/repo',
            ]);

            $service = app(GitHubWebhookService::class);
            $service->register($project);

            Http::assertSent(function ($request) {
                return $request->hasHeader('Authorization', 'Bearer test-token-123');
            });
        });
    });

    describe('deregister', function (): void {
        it('deletes the webhook and clears project columns', function (): void {
            Http::fake([
                'api.github.com/repos/user/repo/hooks/12345' => Http::response(null, 204),
            ]);

            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create([
                'github_repo_url' => 'https://github.com/user/repo',
                'github_webhook_id' => 12345,
                'github_webhook_secret' => 'old-secret',
            ]);

            $service = app(GitHubWebhookService::class);
            $result = $service->deregister($project);

            expect($result)->toBeTrue()
                ->and($project->fresh()->github_webhook_id)->toBeNull()
                ->and($project->fresh()->github_webhook_secret)->toBeNull();
        });

        it('returns true when project has no webhook registered', function (): void {
            Http::fake();

            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create([
                'github_webhook_id' => null,
                'github_webhook_secret' => null,
            ]);

            $service = app(GitHubWebhookService::class);
            $result = $service->deregister($project);

            expect($result)->toBeTrue();
            Http::assertNothingSent();
        });

        it('treats 404 as success when webhook is already deleted', function (): void {
            Http::fake([
                'api.github.com/repos/user/repo/hooks/12345' => Http::response(['message' => 'Not Found'], 404),
            ]);

            $user = User::factory()->create();
            $project = Project::factory()->for($user)->create([
                'github_repo_url' => 'https://github.com/user/repo',
                'github_webhook_id' => 12345,
                'github_webhook_secret' => 'old-secret',
            ]);

            $service = app(GitHubWebhookService::class);
            $result = $service->deregister($project);

            expect($result)->toBeTrue()
                ->and($project->fresh()->github_webhook_id)->toBeNull()
                ->and($project->fresh()->github_webhook_secret)->toBeNull();
        });
    });
});
