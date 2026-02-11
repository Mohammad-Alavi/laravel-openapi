<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\BuildStatus;
use App\Models\Build;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

final class BuildRunner
{
    public function run(Build $build): void
    {
        $build->update(['started_at' => now()]);

        $workspace = storage_path("app/builds/{$build->id}");

        try {
            $this->cloneRepository($build, $workspace);
            $this->runContainer($build, $workspace);
            $this->collectOutput($build, $workspace);

            $build->update(['status' => BuildStatus::Completed]);
        } catch (\RuntimeException $e) {
            $build->update([
                'status' => BuildStatus::Failed,
                'error_log' => $e->getMessage(),
            ]);
        } finally {
            $this->cleanup($workspace);
            $build->update(['completed_at' => now()]);
        }
    }

    private function cloneRepository(Build $build, string $workspace): void
    {
        $project = $build->project;
        $project->loadMissing('user');

        $token = $project->user->github_token;
        $repoPath = ltrim((string) parse_url($project->github_repo_url, PHP_URL_PATH), '/');
        $branch = $project->github_branch;

        $result = Process::run(
            "git clone --depth=1 --branch={$branch} https://x-access-token:{$token}@github.com/{$repoPath}.git {$workspace}/repo"
        );

        if ($result->failed()) {
            throw new \RuntimeException($result->errorOutput());
        }
    }

    private function runContainer(Build $build, string $workspace): void
    {
        $repoDir = "{$workspace}/repo";
        $outputDir = "{$workspace}/output";

        $result = Process::timeout(300)->run(
            "docker run --rm --memory=512m --cpus=1 -v {$repoDir}:/workspace/repo -v {$outputDir}:/workspace/output laragen-build-runner"
        );

        if ($result->failed()) {
            throw new \RuntimeException($result->errorOutput());
        }
    }

    private function collectOutput(Build $build, string $workspace): void
    {
        $outputFile = "{$workspace}/output/openapi.json";
        $storagePath = "builds/{$build->project_id}/{$build->id}/openapi.json";

        if (file_exists($outputFile)) {
            Storage::put($storagePath, file_get_contents($outputFile));
            $build->update(['output_path' => $storagePath]);
        }
    }

    private function cleanup(string $workspace): void
    {
        Process::run("rm -rf {$workspace}");
    }
}
