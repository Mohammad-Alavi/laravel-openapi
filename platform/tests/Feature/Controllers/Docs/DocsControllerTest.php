<?php

use App\Domain\Documentation\Access\Entities\DocAccessLink;
use App\Domain\Documentation\Access\Entities\DocRole;
use App\Domain\Documentation\Access\Entities\DocSetting;
use App\Domain\Documentation\Access\Entities\DocVisibilityRule;
use App\Domain\Documentation\Access\Enums\DocVisibility;
use App\Domain\Documentation\Access\Enums\EndpointVisibility;
use App\Domain\Documentation\Access\Enums\RuleType;
use App\Domain\Documentation\Access\ValueObjects\HashedToken;
use App\Domain\Documentation\Rendering\Events\DocViewed;
use App\Http\Controllers\Docs\DocsController;
use App\Models\Build;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;

function createProjectWithSpec(User $user, array $spec = []): Project
{
    $build = Build::factory()->create();
    $project = Project::factory()->for($user)->create([
        'latest_build_id' => $build->id,
    ]);
    $build->update(['project_id' => $project->id]);

    $defaultSpec = [
        'openapi' => '3.0.0',
        'info' => ['title' => 'Test API', 'version' => '1.0.0'],
        'paths' => [
            '/api/users' => [
                'get' => [
                    'tags' => ['users'],
                    'summary' => 'List users',
                    'responses' => ['200' => ['description' => 'OK']],
                ],
            ],
            '/api/payments' => [
                'post' => [
                    'tags' => ['payments'],
                    'summary' => 'Create payment',
                    'responses' => ['200' => ['description' => 'OK']],
                ],
            ],
        ],
        'tags' => [
            ['name' => 'users'],
            ['name' => 'payments'],
        ],
    ];

    Storage::put(
        "builds/{$project->id}/{$build->id}/openapi.json",
        json_encode(array_merge($defaultSpec, $spec)),
    );

    return $project;
}

describe(class_basename(DocsController::class), function (): void {
    beforeEach(function (): void {
        Storage::fake();
        Event::fake([DocViewed::class]);
    });

    it('returns 404 when project has no builds', function (): void {
        $project = Project::factory()->create();

        $this->get("/docs/{$project->slug}")
            ->assertNotFound();
    });

    it('returns 404 when spec file does not exist', function (): void {
        $user = User::factory()->create();
        $build = Build::factory()->create();
        $project = Project::factory()->for($user)->create([
            'latest_build_id' => $build->id,
        ]);

        $this->get("/docs/{$project->slug}")
            ->assertNotFound();
    });

    it('returns 404 for private docs accessed anonymously', function (): void {
        $user = User::factory()->create();
        $project = createProjectWithSpec($user);

        DocSetting::create([
            'project_id' => $project->id,
            'visibility' => DocVisibility::Private,
        ]);

        $this->get("/docs/{$project->slug}")
            ->assertNotFound();
    });

    it('shows docs for public project accessed anonymously', function (): void {
        $user = User::factory()->create();
        $project = createProjectWithSpec($user);

        DocSetting::create([
            'project_id' => $project->id,
            'visibility' => DocVisibility::Public,
        ]);

        $this->get("/docs/{$project->slug}")
            ->assertOk()
            ->assertViewIs('docs')
            ->assertViewHas('project')
            ->assertViewHas('spec');
    });

    it('owner sees full spec for private docs', function (): void {
        $user = User::factory()->create();
        $project = createProjectWithSpec($user);

        DocSetting::create([
            'project_id' => $project->id,
            'visibility' => DocVisibility::Private,
        ]);

        $response = $this->actingAs($user)
            ->get("/docs/{$project->slug}");

        $response->assertOk();
        $spec = $response->viewData('spec');
        expect(array_keys($spec['paths']))->toContain('/api/users')
            ->and(array_keys($spec['paths']))->toContain('/api/payments');
    });

    it('anonymous sees only public endpoints when visibility rules exist', function (): void {
        $user = User::factory()->create();
        $project = createProjectWithSpec($user);

        DocSetting::create([
            'project_id' => $project->id,
            'visibility' => DocVisibility::Public,
        ]);

        DocVisibilityRule::create([
            'project_id' => $project->id,
            'rule_type' => RuleType::Tag,
            'identifier' => 'payments',
            'visibility' => EndpointVisibility::Restricted,
        ]);

        $response = $this->get("/docs/{$project->slug}");

        $response->assertOk();
        $spec = $response->viewData('spec');
        expect(array_keys($spec['paths']))->toContain('/api/users')
            ->and($spec['paths'])->not->toHaveKey('/api/payments');
    });

    it('token viewer sees role-scoped endpoints', function (): void {
        $user = User::factory()->create();
        $project = createProjectWithSpec($user);

        DocSetting::create([
            'project_id' => $project->id,
            'visibility' => DocVisibility::Public,
        ]);

        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Partner',
            'scopes' => ['payments'],
            'is_default' => false,
        ]);

        DocVisibilityRule::create([
            'project_id' => $project->id,
            'rule_type' => RuleType::Tag,
            'identifier' => 'payments',
            'visibility' => EndpointVisibility::Restricted,
        ]);

        $plainToken = 'test-token-for-access-link-12345678901234567890123456789012';
        $hashedToken = HashedToken::fromPlain($plainToken);

        DocAccessLink::create([
            'project_id' => $project->id,
            'doc_role_id' => $role->id,
            'name' => 'Partner Link',
            'token' => $hashedToken->toString(),
        ]);

        $response = $this->get("/docs/{$project->slug}?token={$plainToken}");

        $response->assertOk();
        $spec = $response->viewData('spec');
        expect(array_keys($spec['paths']))->toContain('/api/users')
            ->and(array_keys($spec['paths']))->toContain('/api/payments');
    });

    it('expired token is treated as anonymous', function (): void {
        $user = User::factory()->create();
        $project = createProjectWithSpec($user);

        DocSetting::create([
            'project_id' => $project->id,
            'visibility' => DocVisibility::Public,
        ]);

        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Partner',
            'scopes' => ['payments'],
            'is_default' => false,
        ]);

        DocVisibilityRule::create([
            'project_id' => $project->id,
            'rule_type' => RuleType::Tag,
            'identifier' => 'payments',
            'visibility' => EndpointVisibility::Restricted,
        ]);

        $plainToken = 'expired-token-12345678901234567890123456789012345678901234';
        $hashedToken = HashedToken::fromPlain($plainToken);

        DocAccessLink::create([
            'project_id' => $project->id,
            'doc_role_id' => $role->id,
            'name' => 'Expired Link',
            'token' => $hashedToken->toString(),
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->get("/docs/{$project->slug}?token={$plainToken}");

        $response->assertOk();
        $spec = $response->viewData('spec');
        expect(array_keys($spec['paths']))->toContain('/api/users')
            ->and($spec['paths'])->not->toHaveKey('/api/payments');
    });

    it('expired token on private docs returns 404', function (): void {
        $user = User::factory()->create();
        $project = createProjectWithSpec($user);

        DocSetting::create([
            'project_id' => $project->id,
            'visibility' => DocVisibility::Private,
        ]);

        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Partner',
            'scopes' => ['*'],
            'is_default' => false,
        ]);

        $plainToken = 'expired-private-123456789012345678901234567890123456789012';
        $hashedToken = HashedToken::fromPlain($plainToken);

        DocAccessLink::create([
            'project_id' => $project->id,
            'doc_role_id' => $role->id,
            'name' => 'Expired Link',
            'token' => $hashedToken->toString(),
            'expires_at' => now()->subDay(),
        ]);

        $this->get("/docs/{$project->slug}?token={$plainToken}")
            ->assertNotFound();
    });

    it('dispatches DocViewed event', function (): void {
        $user = User::factory()->create();
        $project = createProjectWithSpec($user);

        DocSetting::create([
            'project_id' => $project->id,
            'visibility' => DocVisibility::Public,
        ]);

        $this->get("/docs/{$project->slug}")
            ->assertOk();

        Event::assertDispatched(DocViewed::class, function (DocViewed $event) use ($project): bool {
            return $event->projectId === $project->id
                && $event->viewerType === 'anonymous'
                && $event->endpointCount === 2;
        });
    });

    it('dispatches DocViewed with owner viewer type', function (): void {
        $user = User::factory()->create();
        $project = createProjectWithSpec($user);

        DocSetting::create([
            'project_id' => $project->id,
            'visibility' => DocVisibility::Private,
        ]);

        $this->actingAs($user)->get("/docs/{$project->slug}")
            ->assertOk();

        Event::assertDispatched(DocViewed::class, function (DocViewed $event): bool {
            return $event->viewerType === 'owner';
        });
    });

    it('updates last_used_at when access link token is used', function (): void {
        $user = User::factory()->create();
        $project = createProjectWithSpec($user);

        DocSetting::create([
            'project_id' => $project->id,
            'visibility' => DocVisibility::Public,
        ]);

        $role = DocRole::create([
            'project_id' => $project->id,
            'name' => 'Partner',
            'scopes' => ['*'],
            'is_default' => false,
        ]);

        $plainToken = 'touch-last-used-12345678901234567890123456789012345678901';
        $hashedToken = HashedToken::fromPlain($plainToken);

        $link = DocAccessLink::create([
            'project_id' => $project->id,
            'doc_role_id' => $role->id,
            'name' => 'Test Link',
            'token' => $hashedToken->toString(),
        ]);

        expect($link->getLastUsedAt())->toBeNull();

        $this->get("/docs/{$project->slug}?token={$plainToken}")
            ->assertOk();

        $link->refresh();
        expect($link->getLastUsedAt())->not->toBeNull();
    });

    it('defaults to private when no doc setting exists', function (): void {
        $user = User::factory()->create();
        $project = createProjectWithSpec($user);

        // No DocSetting created — defaults to private
        $this->get("/docs/{$project->slug}")
            ->assertNotFound();
    });

    it('renders docs blade view with spec and project', function (): void {
        $user = User::factory()->create();
        $project = createProjectWithSpec($user);

        DocSetting::create([
            'project_id' => $project->id,
            'visibility' => DocVisibility::Public,
        ]);

        $response = $this->get("/docs/{$project->slug}");

        $response->assertOk()
            ->assertViewIs('docs')
            ->assertViewHas('spec', fn ($spec) => $spec['info']['title'] === 'Test API')
            ->assertViewHas('project', fn ($p) => $p->id === $project->id);
    });
})->covers(DocsController::class);
