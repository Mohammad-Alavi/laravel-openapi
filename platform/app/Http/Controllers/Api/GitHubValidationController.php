<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

final class GitHubValidationController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'github_repo_url' => ['required', 'url', 'regex:/^https:\/\/github\.com\/[^\/]+\/[^\/]+$/'],
            'branch' => ['nullable', 'string'],
        ]);

        $url = $request->string('github_repo_url')->toString();
        $path = parse_url($url, PHP_URL_PATH);
        $repoPath = ltrim((string) $path, '/');

        $response = Http::withToken($request->user()->github_token)
            ->get("https://api.github.com/repos/{$repoPath}");

        if ($response->status() === 404) {
            return response()->json([
                'valid' => false,
                'error' => 'Repository not found. Check the URL and your access permissions.',
            ]);
        }

        if ($response->status() === 403) {
            return response()->json([
                'valid' => false,
                'error' => 'You do not have access to this repository.',
            ]);
        }

        if ($response->failed()) {
            return response()->json([
                'valid' => false,
                'error' => 'Unable to verify repository. Please try again.',
            ]);
        }

        $data = $response->json();
        $result = [
            'valid' => true,
            'default_branch' => $data['default_branch'],
            'private' => $data['private'],
        ];

        if ($request->filled('branch')) {
            $branchResponse = Http::withToken($request->user()->github_token)
                ->get("https://api.github.com/repos/{$repoPath}/branches/{$request->string('branch')}");

            $result['branch_valid'] = $branchResponse->successful();
        }

        return response()->json($result);
    }
}
