# Hosted Documentation Plan (Revised)

## Architecture Changes from Original Plan

### 1. Three Service Types (DDD Layering)

| Service Type | Location | Examples |
|-------------|----------|----------|
| **Domain Services** | `Domain/.../Services/` | ScopeMatcher, SpecFilter, SpecParser |
| **Application Services** | `Application/Documentation/Actions/` | CreateDocRole, UpdateDocSetting, etc. |
| **Infrastructure Services** | `Infrastructure/Analytics/` | PostHogService, SendToPostHog |

Events placement:
- **Domain Events** → `Domain/Documentation/Access/Events/` and `Domain/Documentation/Rendering/Events/`
- **Application Events** → `Application/Events/` (BuildCompleted, ProjectCreated)
- **Listeners** → `Infrastructure/` when tied to external services (SendToPostHog), `Application/` for cross-domain orchestration

DTOs live in `Application/Documentation/DTOs/` (not in Domain — they're application-layer concerns).

### 2. Entity = Model + Contract (No Duplication)

**Problem with original plan:** Separate pure PHP entities + Eloquent models = field parity maintenance burden.

**Solution:** Contract (interface) + Entity (extends Model, implements Contract).

```php
// Contract — defines domain behavior API only
// Domain/Documentation/Access/Contracts/DocRole.php
interface DocRole
{
    public function grantsAccessTo(RuleType $ruleType, string $identifier): bool;
    public function getScopes(): ScopeCollection;
    public function isDefault(): bool;
    public function getName(): string;
    public function getProjectId(): int;
}

// Entity — IS the Eloquent model, implements domain behavior
// Domain/Documentation/Access/Entities/DocRole.php
final class DocRole extends Model implements Contracts\DocRole
{
    protected $table = 'doc_roles';
    protected $fillable = ['project_id', 'name', 'scopes', 'is_default'];

    public function grantsAccessTo(RuleType $ruleType, string $identifier): bool
    {
        return $this->getScopes()->matchesAny($identifier);
    }

    public function getScopes(): ScopeCollection
    {
        return ScopeCollection::fromArray($this->scopes);
    }
}
```

Benefits:
- No mapping overhead (no `toEntity()` in repositories)
- No field parity maintenance
- Eloquent features (relationships, casts, scopes) available
- Domain behavior exposed through clean interface
- Repositories type-hint against contract (interface), not concrete model

### Revised Directory Structure

```
platform/app/
├── Domain/
│   └── Documentation/
│       ├── Access/
│       │   ├── Contracts/              # Entity behavior interfaces
│       │   │   ├── DocRole.php
│       │   │   ├── DocSetting.php
│       │   │   ├── DocVisibilityRule.php
│       │   │   └── DocAccessLink.php
│       │   ├── Entities/               # Eloquent models implementing contracts
│       │   │   ├── DocRole.php
│       │   │   ├── DocSetting.php
│       │   │   ├── DocVisibilityRule.php
│       │   │   └── DocAccessLink.php
│       │   ├── ValueObjects/
│       │   │   ├── ViewerContext.php
│       │   │   ├── Scope.php
│       │   │   ├── ScopeCollection.php
│       │   │   ├── HashedToken.php
│       │   │   └── PlainToken.php
│       │   ├── Enums/
│       │   │   ├── DocVisibility.php
│       │   │   ├── EndpointVisibility.php
│       │   │   └── RuleType.php
│       │   ├── Events/                 # Domain events
│       │   │   ├── DocSettingUpdated.php
│       │   │   ├── DocRoleCreated.php
│       │   │   ├── DocRoleUpdated.php
│       │   │   ├── DocRoleDeleted.php
│       │   │   ├── VisibilityRuleCreated.php
│       │   │   ├── VisibilityRuleUpdated.php
│       │   │   ├── VisibilityRuleDeleted.php
│       │   │   ├── AccessLinkCreated.php
│       │   │   └── AccessLinkRevoked.php
│       │   ├── Repositories/           # Interfaces only
│       │   │   ├── DocSettingRepository.php
│       │   │   ├── DocRoleRepository.php
│       │   │   ├── DocVisibilityRuleRepository.php
│       │   │   └── DocAccessLinkRepository.php
│       │   └── Services/               # Domain services
│       │       └── ScopeMatcher.php
│       └── Rendering/
│           ├── Services/               # Domain services
│           │   ├── SpecFilter.php
│           │   └── SpecParser.php
│           ├── DTOs/
│           │   ├── SpecTagData.php
│           │   └── SpecPathData.php
│           └── Events/
│               └── DocViewed.php
├── Application/
│   └── Documentation/
│       ├── Actions/                    # Application services (command handlers)
│       │   ├── CreateDocRole.php
│       │   ├── UpdateDocRole.php
│       │   ├── DeleteDocRole.php
│       │   ├── UpdateDocSetting.php
│       │   ├── CreateVisibilityRule.php
│       │   ├── UpdateVisibilityRule.php
│       │   ├── DeleteVisibilityRule.php
│       │   ├── CreateAccessLink.php
│       │   └── RevokeAccessLink.php
│       ├── DTOs/                       # Input/Output DTOs (spatie/laravel-data)
│       │   ├── DocSettingData.php
│       │   ├── DocRoleData.php
│       │   ├── DocVisibilityRuleData.php
│       │   ├── DocAccessLinkData.php
│       │   ├── CreateDocRoleData.php
│       │   ├── UpdateDocRoleData.php
│       │   ├── UpdateDocSettingData.php
│       │   ├── CreateVisibilityRuleData.php
│       │   ├── UpdateVisibilityRuleData.php
│       │   └── CreateAccessLinkData.php
│       └── Events/                     # Application events
│           ├── BuildCompleted.php
│           └── ProjectCreated.php
├── Infrastructure/
│   ├── Documentation/
│   │   ├── Repositories/              # Eloquent implementations
│   │   │   ├── EloquentDocSettingRepository.php
│   │   │   ├── EloquentDocRoleRepository.php
│   │   │   ├── EloquentDocVisibilityRuleRepository.php
│   │   │   └── EloquentDocAccessLinkRepository.php
│   │   └── Providers/
│   │       └── DocumentationServiceProvider.php
│   └── Analytics/                     # Infrastructure services
│       ├── PostHogService.php
│       └── SendToPostHog.php
├── Http/
│   └── Controllers/
│       └── Docs/
│           ├── DocsController.php
│           ├── DocSettingController.php
│           ├── DocRoleController.php
│           ├── DocVisibilityRuleController.php
│           └── DocAccessLinkController.php
└── ... (existing structure unchanged)
```

## Data Flow

```
HTTP Request
  → Controller (thin adapter, validates via Data input DTO)
    → Action (receives DTO, calls Repository, dispatches Event)
      → Repository interface (domain layer, type-hinted as Contract)
        → Eloquent implementation (queries Entity which IS the Model)
          → returns Entity (typed as Contract interface)
      → Domain Event dispatched
        → SendToPostHog listener → PostHog
    → returns Contract or Output DTO
  → Controller maps to Output DTO → Inertia / JSON response
```

## Implementation Phases

See original plan for full details. Key changes:
- No separate Eloquent models in Infrastructure (eliminated)
- Actions moved to Application layer
- DTOs moved to Application layer
- PostHog moved to Infrastructure/Analytics
- Repositories in Infrastructure are thinner (no entity mapping)

## Phase 1 Scope (Current Implementation)

1. Domain enums + latest_build_id migration ✅
2. Value Objects (Scope, ScopeCollection, HashedToken, PlainToken) ✅
3. Contracts (interfaces) + Entities (Model + Contract) + ViewerContext ✅
4. Repository interfaces + DTOs + Eloquent implementations + migrations ✅
5. SpecFilter + SpecParser domain services
6. Domain events
7. PostHog analytics
8. Actions (command handlers)
9. DocsController + Blade view (Scalar)
10. Management controllers + routes
11. ProjectController.show updates
12. Vue management UI components

## Future Performance Improvements

### Repository Caching (Decorator Pattern)

Add caching via decorator pattern — zero changes to existing repository implementations:

```
DocRoleRepository (interface)
  └── CachingDocRoleRepository (decorator — cache layer)
        └── EloquentDocRoleRepository (actual DB queries)
```

Hot candidates for caching:
- **DocSetting** — read on every doc page view, rarely changes (cache per project, invalidate on update)
- **DocRole** — read on every authenticated doc view, rarely changes (cache per project)
- **DocVisibilityRule** — read on every doc view for filtering (cache per project)

Implementation: `CachingDocRoleRepository` wraps the Eloquent implementation, uses Laravel Cache with per-project keys. Invalidation on writes via cache tags or explicit key deletion.

### Other Performance Improvements

- **Spec file caching** — the JSON file is on disk, but parsed spec can be cached in memory/Redis
- **CDN for Scalar assets** — already on CDN via Scalar's CDN
- **Route model binding cache** — for frequently accessed projects by slug
- **Eager loading** — already done in repository implementations where needed
- **Database query caching** — consider when traffic justifies it

## Phase 2: Members + Teams (future)

- `doc_members` table — email invitations with role assignment
- `doc_teams` table — group members, team gets a role
- Member invitation email notification
- Management UI: Members tab, Teams tab

## Phase 3: Domain Access + Password + Analytics (future)

- `doc_domain_rules` table — `@company.com` → default role
- Password protection on `doc_settings`
- View analytics dashboard
- Repository caching decorator implementation
