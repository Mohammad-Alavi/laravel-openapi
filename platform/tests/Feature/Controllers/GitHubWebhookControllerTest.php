<?php

declare(strict_types=1);

use App\Enums\ProjectStatus;
use App\Jobs\ProcessGitHubPushJob;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

describe('GitHubWebhookController', function (): void {
    it('dispatches a job for a valid push event', function (): void {
        Queue::fake();

        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'github_repo_url' => 'https://github.com/owner/repo',
            'github_branch' => 'main',
            'github_webhook_secret' => 'test-secret',
            'status' => ProjectStatus::Active,
        ]);

        $payload = json_encode([
            'ref' => 'refs/heads/main',
            'after' => 'abc123def456',
            'repository' => ['full_name' => 'owner/repo'],
        ]);

        $signature = 'sha256=' . hash_hmac('sha256', $payload, 'test-secret');

        $this->postJson('/webhooks/github', json_decode($payload, true), [
            'X-GitHub-Event' => 'push',
            'X-Hub-Signature-256' => $signature,
        ])->assertOk();

        Queue::assertPushed(ProcessGitHubPushJob::class, function ($job) use ($project) {
            return $job->project->id === $project->id
                && $job->commitSha === 'abc123def456';
        });
    });

    it('returns 200 for non-push events like ping', function (): void {
        Queue::fake();

        $this->postJson('/webhooks/github', ['zen' => 'testing'], [
            'X-GitHub-Event' => 'ping',
        ])->assertOk();

        Queue::assertNothingPushed();
    });

    it('returns 400 when repository.full_name is missing', function (): void {
        $payload = json_encode([
            'ref' => 'refs/heads/main',
            'after' => 'abc123',
        ]);

        $this->postJson('/webhooks/github', json_decode($payload, true), [
            'X-GitHub-Event' => 'push',
        ])->assertBadRequest();
    });

    it('returns 404 when no project matches the repository', function (): void {
        $payload = json_encode([
            'ref' => 'refs/heads/main',
            'after' => 'abc123',
            'repository' => ['full_name' => 'unknown/repo'],
        ]);

        $this->postJson('/webhooks/github', json_decode($payload, true), [
            'X-GitHub-Event' => 'push',
        ])->assertNotFound();
    });

    it('returns 403 for invalid signature', function (): void {
        $user = User::factory()->create();
        Project::factory()->for($user)->create([
            'github_repo_url' => 'https://github.com/owner/repo',
            'github_branch' => 'main',
            'github_webhook_secret' => 'real-secret',
            'status' => ProjectStatus::Active,
        ]);

        $payload = json_encode([
            'ref' => 'refs/heads/main',
            'after' => 'abc123',
            'repository' => ['full_name' => 'owner/repo'],
        ]);

        $this->postJson('/webhooks/github', json_decode($payload, true), [
            'X-GitHub-Event' => 'push',
            'X-Hub-Signature-256' => 'sha256=invalidsignature',
        ])->assertForbidden();
    });

    it('returns 403 when signature header is missing', function (): void {
        $user = User::factory()->create();
        Project::factory()->for($user)->create([
            'github_repo_url' => 'https://github.com/owner/repo',
            'github_branch' => 'main',
            'github_webhook_secret' => 'real-secret',
            'status' => ProjectStatus::Active,
        ]);

        $payload = json_encode([
            'ref' => 'refs/heads/main',
            'after' => 'abc123',
            'repository' => ['full_name' => 'owner/repo'],
        ]);

        $this->postJson('/webhooks/github', json_decode($payload, true), [
            'X-GitHub-Event' => 'push',
        ])->assertForbidden();
    });

    it('ignores pushes to non-tracked branches', function (): void {
        Queue::fake();

        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create([
            'github_repo_url' => 'https://github.com/owner/repo',
            'github_branch' => 'main',
            'github_webhook_secret' => 'test-secret',
            'status' => ProjectStatus::Active,
        ]);

        $payload = json_encode([
            'ref' => 'refs/heads/develop',
            'after' => 'abc123',
            'repository' => ['full_name' => 'owner/repo'],
        ]);

        $signature = 'sha256=' . hash_hmac('sha256', $payload, 'test-secret');

        $this->postJson('/webhooks/github', json_decode($payload, true), [
            'X-GitHub-Event' => 'push',
            'X-Hub-Signature-256' => $signature,
        ])->assertOk();

        Queue::assertNothingPushed();
    });

    it('skips paused projects', function (): void {
        Queue::fake();

        $user = User::factory()->create();
        Project::factory()->for($user)->create([
            'github_repo_url' => 'https://github.com/owner/repo',
            'github_branch' => 'main',
            'github_webhook_secret' => 'test-secret',
            'status' => ProjectStatus::Paused,
        ]);

        $payload = json_encode([
            'ref' => 'refs/heads/main',
            'after' => 'abc123',
            'repository' => ['full_name' => 'owner/repo'],
        ]);

        $signature = 'sha256=' . hash_hmac('sha256', $payload, 'test-secret');

        $this->postJson('/webhooks/github', json_decode($payload, true), [
            'X-GitHub-Event' => 'push',
            'X-Hub-Signature-256' => $signature,
        ])->assertOk();

        Queue::assertNothingPushed();
    });

    it('skips projects already building', function (): void {
        Queue::fake();

        $user = User::factory()->create();
        Project::factory()->for($user)->create([
            'github_repo_url' => 'https://github.com/owner/repo',
            'github_branch' => 'main',
            'github_webhook_secret' => 'test-secret',
            'status' => ProjectStatus::Building,
        ]);

        $payload = json_encode([
            'ref' => 'refs/heads/main',
            'after' => 'abc123',
            'repository' => ['full_name' => 'owner/repo'],
        ]);

        $signature = 'sha256=' . hash_hmac('sha256', $payload, 'test-secret');

        $this->postJson('/webhooks/github', json_decode($payload, true), [
            'X-GitHub-Event' => 'push',
            'X-Hub-Signature-256' => $signature,
        ])->assertOk();

        Queue::assertNothingPushed();
    });
});
