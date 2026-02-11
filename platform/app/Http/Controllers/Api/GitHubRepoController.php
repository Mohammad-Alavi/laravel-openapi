<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

final class GitHubRepoController extends Controller
{
    public function repos(Request $request): JsonResponse
    {
        $response = Http::withToken($request->user()->github_token)
            ->get('https://api.github.com/user/repos', [
                'per_page' => 100,
                'sort' => 'updated',
                'type' => 'all',
            ]);

        if ($response->failed()) {
            return response()->json([]);
        }

        $repos = collect($response->json());

        $query = $request->string('q')->toString();
        if ($query !== '') {
            $repos = $repos->filter(
                fn (array $repo): bool => str_contains(
                    mb_strtolower($repo['full_name']),
                    mb_strtolower($query),
                ),
            );
        }

        return response()->json(
            $repos->take(25)->map(fn (array $repo): array => [
                'full_name' => $repo['full_name'],
                'name' => $repo['name'],
                'description' => $repo['description'],
                'url' => $repo['html_url'],
                'default_branch' => $repo['default_branch'],
                'private' => $repo['private'],
            ])->values()->all(),
        );
    }

    public function branches(Request $request): JsonResponse
    {
        $request->validate([
            'repo' => ['required', 'string', 'regex:/^[^\/]+\/[^\/]+$/'],
        ]);

        $repo = $request->string('repo')->toString();

        $response = Http::withToken($request->user()->github_token)
            ->get("https://api.github.com/repos/{$repo}/branches", [
                'per_page' => 100,
            ]);

        if ($response->failed()) {
            return response()->json([]);
        }

        return response()->json(
            collect($response->json())->pluck('name')->all(),
        );
    }
}
