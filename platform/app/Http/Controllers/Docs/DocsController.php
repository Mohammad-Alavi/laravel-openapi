<?php

namespace App\Http\Controllers\Docs;

use App\Domain\Documentation\Access\Entities\DocAccessLink;
use App\Domain\Documentation\Access\Entities\DocRole;
use App\Domain\Documentation\Access\Enums\DocVisibility;
use App\Domain\Documentation\Access\Repositories\DocAccessLinkRepository;
use App\Domain\Documentation\Access\Repositories\DocSettingRepository;
use App\Domain\Documentation\Access\Repositories\DocVisibilityRuleRepository;
use App\Domain\Documentation\Access\ValueObjects\HashedToken;
use App\Domain\Documentation\Access\ValueObjects\ViewerContext;
use App\Domain\Documentation\Rendering\Events\DocViewed;
use App\Domain\Documentation\Rendering\Services\SpecFilter;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DocsController extends Controller
{
    public function __construct(
        private readonly DocSettingRepository $settingRepository,
        private readonly DocAccessLinkRepository $linkRepository,
        private readonly DocVisibilityRuleRepository $ruleRepository,
        private readonly SpecFilter $specFilter,
    ) {}

    public function __invoke(Request $request, Project $project): View
    {
        $viewer = $this->resolveViewer($request, $project);
        $setting = $this->settingRepository->findByProjectId($project->id);
        $isPrivate = $setting?->getVisibility() === DocVisibility::Private
            || $setting === null;

        if ($isPrivate && $viewer->isAnonymous()) {
            throw new NotFoundHttpException();
        }

        if ($project->latest_build_id === null) {
            throw new NotFoundHttpException();
        }

        $specPath = "builds/{$project->id}/{$project->latest_build_id}/openapi.json";
        if (! Storage::exists($specPath)) {
            throw new NotFoundHttpException();
        }

        $spec = json_decode(Storage::get($specPath), true);
        $rules = $this->ruleRepository->findByProjectId($project->id);
        $filtered = $this->specFilter->filter($spec, $viewer, $rules);

        $endpointCount = 0;
        foreach ($filtered['paths'] ?? [] as $methods) {
            $endpointCount += count(array_filter($methods, 'is_array'));
        }

        DocViewed::dispatch(
            $project->id,
            $this->viewerType($viewer),
            $viewer->role()?->getName(),
            $endpointCount,
        );

        return view('docs', [
            'spec' => $filtered,
            'project' => $project,
        ]);
    }

    private function resolveViewer(Request $request, Project $project): ViewerContext
    {
        if ($request->user()?->id === $project->user_id) {
            return ViewerContext::owner();
        }

        $token = $request->query('token');
        if (is_string($token) && $token !== '') {
            $hashedToken = HashedToken::fromPlain($token);
            $link = $this->linkRepository->findByToken($hashedToken->toString());

            if ($link !== null && $link->getProjectId() === $project->id && $link->isValid()) {
                $this->linkRepository->touchLastUsed($link->getUlid());
                $role = DocRole::where('ulid', $link->getDocRoleUlid())->first();

                if ($role !== null) {
                    return ViewerContext::withRole($role);
                }
            }
        }

        return ViewerContext::anonymous();
    }

    private function viewerType(ViewerContext $viewer): string
    {
        if ($viewer->isOwner()) {
            return 'owner';
        }

        if ($viewer->hasRole()) {
            return 'role';
        }

        return 'anonymous';
    }
}
