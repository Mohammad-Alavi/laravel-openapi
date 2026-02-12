<?php

namespace App\Http\Controllers\Webhooks;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessGitHubPushJob;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GitHubWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $event = $request->header('X-GitHub-Event');

        if ($event !== 'push') {
            return response()->json(['message' => 'ok']);
        }

        $fullName = $request->input('repository.full_name');

        if ($fullName === null) {
            return response()->json(['message' => 'Missing repository.full_name'], 400);
        }

        $project = Project::where('github_repo_url', 'https://github.com/' . $fullName)->first();

        if ($project === null) {
            return response()->json(['message' => 'No matching project'], 404);
        }

        if (! $this->verifySignature($request, $project->github_webhook_secret)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $branch = str_replace('refs/heads/', '', (string) $request->input('ref'));

        if ($branch !== $project->github_branch) {
            return response()->json(['message' => 'Branch ignored']);
        }

        if ($project->status !== ProjectStatus::Active) {
            return response()->json(['message' => 'Project not active']);
        }

        ProcessGitHubPushJob::dispatch($project, (string) $request->input('after'));

        return response()->json(['message' => 'ok']);
    }

    private function verifySignature(Request $request, ?string $secret): bool
    {
        $signature = $request->header('X-Hub-Signature-256');

        if ($signature === null || $secret === null) {
            return false;
        }

        $expected = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);

        return hash_equals($expected, $signature);
    }
}
