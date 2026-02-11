<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

final class GitHubWebhookService
{
    public function register(Project $project): bool
    {
        $secret = Str::random(40);
        $repoPath = $this->extractRepoPath($project->github_repo_url);

        $project->loadMissing('user');

        $response = Http::withToken($project->user->github_token)
            ->post("https://api.github.com/repos/{$repoPath}/hooks", [
                'name' => 'web',
                'active' => true,
                'events' => ['push'],
                'config' => [
                    'url' => config('services.github.webhook_url'),
                    'content_type' => 'json',
                    'secret' => $secret,
                    'insecure_ssl' => '0',
                ],
            ]);

        if ($response->failed()) {
            return false;
        }

        $project->update([
            'github_webhook_id' => $response->json('id'),
            'github_webhook_secret' => $secret,
        ]);

        return true;
    }

    public function deregister(Project $project): bool
    {
        if ($project->github_webhook_id === null) {
            return true;
        }

        $repoPath = $this->extractRepoPath($project->github_repo_url);

        $project->loadMissing('user');

        $response = Http::withToken($project->user->github_token)
            ->delete("https://api.github.com/repos/{$repoPath}/hooks/{$project->github_webhook_id}");

        if ($response->failed() && $response->status() !== 404) {
            return false;
        }

        $project->update([
            'github_webhook_id' => null,
            'github_webhook_secret' => null,
        ]);

        return true;
    }

    private function extractRepoPath(string $url): string
    {
        return ltrim((string) parse_url($url, PHP_URL_PATH), '/');
    }
}
