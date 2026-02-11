<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\Documentation\DTOs\DocAccessLinkData;
use App\Application\Documentation\DTOs\DocRoleData;
use App\Application\Documentation\DTOs\DocSettingData;
use App\Application\Documentation\DTOs\DocVisibilityRuleData;
use App\Application\DTOs\ProjectData;
use App\Domain\Documentation\Access\Repositories\DocAccessLinkRepository;
use App\Domain\Documentation\Access\Repositories\DocRoleRepository;
use App\Domain\Documentation\Access\Repositories\DocSettingRepository;
use App\Domain\Documentation\Access\Repositories\DocVisibilityRuleRepository;
use App\Domain\Documentation\Rendering\Services\SpecParser;
use App\Enums\ProjectStatus;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\GitHubWebhookService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

final class ProjectController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly GitHubWebhookService $webhookService,
        private readonly DocSettingRepository $docSettingRepository,
        private readonly DocRoleRepository $docRoleRepository,
        private readonly DocVisibilityRuleRepository $docVisibilityRuleRepository,
        private readonly DocAccessLinkRepository $docAccessLinkRepository,
        private readonly SpecParser $specParser,
    ) {}

    public function index(Request $request): Response
    {
        $allProjects = $request->user()->projects();

        $stats = [
            'total' => $allProjects->count(),
            'active' => $allProjects->clone()->where('status', ProjectStatus::Active)->count(),
            'paused' => $allProjects->clone()->where('status', ProjectStatus::Paused)->count(),
            'building' => $allProjects->clone()->where('status', ProjectStatus::Building)->count(),
        ];

        $query = $request->user()->projects()->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->string('search') . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $projects = $query->paginate(12)->withQueryString()
            ->through(fn (Project $project) => ProjectData::fromModel($project));

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
            'stats' => $stats,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Projects/Create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = $request->user()->projects()->create($request->validated());

        $this->webhookService->register($project);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project): Response
    {
        $this->authorize('view', $project);

        $docSetting = $this->docSettingRepository->findByProjectId($project->id);
        $docRoles = $this->docRoleRepository->findByProjectId($project->id);
        $docRules = $this->docVisibilityRuleRepository->findByProjectId($project->id);
        $docLinks = $this->docAccessLinkRepository->findByProjectId($project->id);

        $specTags = [];
        $specPaths = [];
        if ($project->latest_build_id !== null) {
            $specPath = "builds/{$project->id}/{$project->latest_build_id}/openapi.json";
            if (Storage::exists($specPath)) {
                $spec = json_decode(Storage::get($specPath), true);
                $specTags = $this->specParser->extractTags($spec);
                $specPaths = $this->specParser->extractPaths($spec);
            }
        }

        return Inertia::render('Projects/Show', [
            'project' => ProjectData::fromModel($project),
            'docSetting' => $docSetting ? DocSettingData::fromContract($docSetting) : null,
            'docRoles' => array_map(fn ($r) => DocRoleData::fromContract($r), $docRoles),
            'docRules' => array_map(fn ($r) => DocVisibilityRuleData::fromContract($r), $docRules),
            'docLinks' => array_map(fn ($l) => DocAccessLinkData::fromContract($l), $docLinks),
            'specTags' => $specTags,
            'specPaths' => $specPaths,
        ]);
    }

    public function edit(Project $project): Response
    {
        $this->authorize('update', $project);

        return Inertia::render('Projects/Edit', [
            'project' => ProjectData::fromModel($project),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $repoChanged = $request->has('github_repo_url')
            && $request->input('github_repo_url') !== $project->github_repo_url;

        if ($repoChanged) {
            $this->webhookService->deregister($project);
        }

        $project->update($request->validated());

        if ($repoChanged) {
            $this->webhookService->register($project);
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $this->webhookService->deregister($project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
