<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

final class ProjectStatusController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $data = [
            'status' => $project->status->value,
            'last_built_at' => $project->last_built_at?->toJSON(),
        ];

        if ($project->status === ProjectStatus::Building) {
            $latestBuild = $project->builds()->latest()->first();

            if ($latestBuild !== null) {
                $data['latest_build'] = [
                    'commit_sha' => $latestBuild->commit_sha,
                    'status' => $latestBuild->status->value,
                ];
            }
        }

        return response()->json($data);
    }
}
