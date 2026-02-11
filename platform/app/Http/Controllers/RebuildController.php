<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Jobs\ProcessGitHubPushJob;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

final class RebuildController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        if ($project->status === ProjectStatus::Building) {
            return redirect()->back()->with('error', 'A build is already in progress.');
        }

        $project->loadMissing('user');
        $repoPath = ltrim((string) parse_url($project->github_repo_url, PHP_URL_PATH), '/');

        $response = Http::withToken($project->user->github_token)
            ->get("https://api.github.com/repos/{$repoPath}/commits/{$project->github_branch}");

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Failed to fetch latest commit from GitHub.');
        }

        $project->update(['status' => ProjectStatus::Building]);

        ProcessGitHubPushJob::dispatch($project, $response->json('sha'));

        return redirect()->back()->with('success', 'Build started successfully.');
    }
}
