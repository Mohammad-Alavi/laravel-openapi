<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Application\DTOs\BuildStatusData;
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

        return response()->json(BuildStatusData::fromProject($project));
    }
}
