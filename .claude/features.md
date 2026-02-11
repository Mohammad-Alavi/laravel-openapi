# Features

See `architecture.md` for package structure and directory layout.

## Laragen Features (All Implemented)

### F1: Route Discovery (Auto)

Discovers API routes by URI patterns without requiring `#[Collection]` attributes.

**Config**: `route_discovery.mode` (`auto` | `attribute` | `combined`), `include`/`exclude` patterns

**Implementation**: `laragen/RouteDiscovery/` — `AutoRouteCollector`, `PatternMatcher`

---

### F2: Path Parameter Detection

Detects path parameter types from route constraints and generates typed `Parameter` objects.

**Config**: `autogen.path_parameters` flag

| Route Constraint | Schema Result |
|-----------------|---------------|
| `whereUuid()` | `string` + `format: uuid` |
| `whereAlpha()` | `string` + `pattern: [a-zA-Z]+` |
| `whereAlphaNumeric()` | `string` + `pattern: [a-zA-Z0-9]+` |
| `whereNumber()` | `integer` |
| `whereUlid()` | `string` + ULID pattern |
| `whereIn()` | `enum` with extracted values |

**Implementation**: `laragen/PathParameters/PathParameterAnalyzer.php`

---

### F3: FormRequest Extraction

Converts Laravel FormRequest validation rules to OpenAPI request body schemas via the `laravel-rules-to-schema/` package (26 parsers).

| Laravel Rule | JSON Schema |
|--------------|-------------|
| `required` | Added to `required` array |
| `string` | `type: string` |
| `integer` | `type: integer` |
| `numeric` | `type: number` |
| `boolean` | `type: boolean` |
| `array` | `type: array` |
| `email` | `type: string, format: email` |
| `url` | `type: string, format: uri` |
| `uuid` | `type: string, format: uuid` |
| `date` | `type: string, format: date` |
| `min:N` / `max:N` | `minLength`/`maxLength` or `minimum`/`maximum` |
| `in:a,b,c` | `enum: ["a", "b", "c"]` |
| `regex:/pattern/` | `pattern: "pattern"` |
| `nullable` | Wrapped with null in oneOf |
| `sometimes` | Field excluded from `required` array |
| `starts_with:foo,bar` | `pattern: ^(foo\|bar)` |
| `ends_with:foo,bar` | `pattern: (foo\|bar)$` |
| `doesnt_start_with:foo,bar` | `pattern: ^(?!foo\|bar)` |
| `doesnt_end_with:foo,bar` | `pattern: (?!.*(foo\|bar)$)` |
| `lowercase` | `pattern: ^[^A-Z]*$` |
| `uppercase` | `pattern: ^[^a-z]*$` |
| `ascii` | `pattern: ^[\x20-\x7E]*$` |
| `hex_color` | `pattern: ^#([0-9a-fA-F]{3}\|[0-9a-fA-F]{6})$` |
| `between:3,10` | `minLength`/`maxLength`, `minimum`/`maximum`, or `minItems`/`maxItems` (type-aware) |
| `size:5` | Same as `between` with equal min/max |
| `multiple_of:3` | `multipleOf: 3` |
| `max_digits:5` | `maximum: 99999` |
| `min_digits:3` | `minimum: 100` |
| `not_in:a,b,c` | `not: { enum: ["a", "b", "c"] }` |
| `accepted` / `declined` | `type: boolean` |
| `active_url` | `format: uri` |
| `timezone` | `format: timezone` |
| `filled` | `minLength: 1` (string) or `minItems: 1` (array) |
| `distinct` | `uniqueItems: true` |
| `extensions:jpg,png` | `enum: ["jpg", "png"]` |
| `required_if:field,value` | `if/then` conditional required |
| `required_unless:field,value` | `if/then/else` conditional required |
| `required_with:a` | `if/then` field present → required |
| `required_with:a,b` | `if: { anyOf }` any field present → required |
| `required_without:a` | `if: { not: { required } }` field absent → required |
| `required_without:a,b` | `if: { not: { required } }` all fields absent → required |
| `required_with_all:a,b` | `if/then` all fields present → required |
| `required_without_all:a,b` | `if/then` all fields absent → required |
| `required_if_accepted:field` | `if/then` field true → required |
| `required_if_declined:field` | `if/then` field false → required |
| `exclude_if` / `exclude_unless` / `exclude_with` / `exclude_without` | `if/then` conditional property removal |
| `missing_if` / `missing_unless` / `missing_with` / `missing_with_all` | `if/then` conditional property removal |
| `prohibited_if` / `prohibited_unless` | `if/then` conditional prohibition |
| `prohibits:a,b` | When present, prohibit other fields |
| `present` | Add to `required`, allow any value |
| `present_if` / `present_unless` / `present_with` / `present_with_all` | `if/then` conditional required presence |
| `accepted_if:field,value` | `if/then` conditional `type: boolean, const: true` |
| `declined_if:field,value` | `if/then` conditional `type: boolean, const: false` |

**Implementation**: `laragen/RequestSchema/` + `laravel-rules-to-schema/Parsers/`

---

### F4: Response Schema Detection (Multi-Strategy)

Pluggable `ResponseStrategy` chain analyzes controller return types to auto-generate response schemas.

**Strategy chain** (tried in order, first match wins):
1. **Annotation** -- detects `@response` docblock tags, infers schema from JSON example
2. **ResourceCollection** -- detects `ResourceCollection` return type, resolves inner resource
3. **JsonResource** -- detects `JsonResource` return type, analyzes `toArray()` AST
4. **SpatieData** -- conditional on `spatie/laravel-data`, analyzes constructor params
5. **FractalTransformer** -- conditional on `league/fractal`, detects transformer references in controller AST
6. **EloquentModel** -- detects `Model` return type, delegates to `ModelSchemaInferrer`

Each strategy has a detector + builder pair. `ResponseSchemaResolver` iterates the chain. Generic AST analysis lives in `ArraySchemaAnalyzer` (reusable across strategies).

**Implementation**: `laragen/ResponseSchema/`

---

### F5: Model Schema Inference

Generates JSON Schema from Eloquent model `$casts`, excludes `$hidden`, includes `$appends`.

| Laravel Cast | JSON Schema |
|-------------|-------------|
| `int`, `integer` | `integer` |
| `float`, `double`, `real` | `number` |
| `string` | `string` |
| `bool`, `boolean` | `boolean` |
| `array`, `collection`, `object` | `object` |
| `date`, `datetime`, `immutable_date`, `immutable_datetime` | `string` + `format: date-time` |
| `timestamp` | `integer` |
| `decimal:N` | `string` |
| Backed enum | `enum` with case values |

**Implementation**: `laragen/ModelSchema/` — `ModelSchemaInferrer`, `CastAnalyzer`

---

### F6: Authentication Detection

Detects auth middleware on routes and generates SecurityScheme components + per-operation security.

**Config**: `autogen.security` flag

| Middleware | SecurityScheme |
|-----------|---------------|
| `auth:sanctum` | Bearer token |
| `auth:api` (Passport) | Bearer token |
| `auth.basic` | HTTP Basic |
| `auth:*` (generic) | Bearer token with guard name |

**Implementation**: `laragen/Auth/` — `AuthDetector`, `AuthScheme`, `SecuritySchemeRegistry`

---

### F7: Request Schema Detection (Multi-Strategy)

Pluggable `RequestStrategy` chain detects request schema from various sources.

**Strategy chain** (tried in order, first match wins):
1. **AnnotationBodyParam** -- detects `@bodyParam` docblock tags, builds typed object schema
2. **AnnotationQueryParam** -- detects `@queryParam` docblock tags, builds typed object schema
3. **SpatieData** -- conditional on `spatie/laravel-data`, detects Data parameter type-hints
4. **ValidationRules** -- extracts FormRequest/inline validation rules, converts to JSON Schema

Each strategy has a detector + builder pair. `RequestSchemaResolver` iterates the chain.

**Implementation**: `laragen/RequestSchema/`

---

### F8: Docblock Annotation Support

Scribe-compatible docblock annotations that override automatic code analysis.

**Supported tags**:
- `@response {status?} {json}`
- `@bodyParam {name} {type} {required?} {description?}`
- `@queryParam {name} {type?} {description?}`

Annotations are checked first in the strategy chain, so they always override automatic detection.

**Implementation**: `laragen/Annotations/` — `DocBlockTagParser`, `DetectedResponseAnnotation`, `DetectedBodyParam`, `DetectedQueryParam`

---

## Platform Features (Implemented)

### Phase 1: Core Pipeline (Implemented)

- **GitHub OAuth** -- Connect repos via OAuth, token stored encrypted
- **Project CRUD** -- Create/edit/delete projects with GitHub repo URL + branch
- **Webhook Processing** -- Auto-rebuild on push events via GitHub webhooks
- **Containerized Analysis** -- Isolated Docker containers for user code (512MB memory, 5min timeout)
- **Build Pipeline** -- GitHub Push → Webhook → Queue → Docker → Storage

### Phase 2: Polish Features (Implemented)

- **Build Status Polling** -- Show page polls `/projects/{id}/status` every 5s when building, auto-reloads on completion. `ProjectStatusController` returns JSON with status, last_built_at, and latest build info.
- **Manual Rebuild** -- "Rebuild" button on project show page fetches latest commit from GitHub API and dispatches `ProcessGitHubPushJob`. Disabled during active builds, returns 409 if already building.
- **Build Retention/Cleanup** -- `builds:cleanup` artisan command runs daily. Retention: keep union of last 10 builds per project OR builds < 30 days old (whichever keeps more). Deletes associated storage files.
- **Build Notifications** -- In-app (database) + email notifications on build completion. `BuildCompletedNotification` sent via mail and database channels. Notification bell with unread badge in app bar. Notifications page with mark-as-read per item and bulk.

### Planned Platform Features (Future)

- **Hosted Documentation** -- Interactive docs with Stoplight Elements
- **Custom Domains** -- User's own domain for hosted docs
- **Changelog Generation** -- Detect API changes between versions
- **Breaking Change Detection** -- Alert on breaking API changes
- **Team Collaboration** -- Org-based role access
- **Billing** -- Stripe subscriptions
- **Frontend Tests with Pest visit()** -- Add browser-level tests using Pest's `visit()` method targeting 100% coverage for both backend and frontend
- **Custom Branding** -- Allow users to customize branding (logo, colors, fonts) of generated documentation
- **Access Management Autocompletion** -- When a doc has been generated, use the latest spec to provide tag/path autocompletion in role scopes and endpoint rules, with examples so users know what's possible
- **Sync Edit Page with Create Page** -- The create and edit project pages have diverged; update Edit.vue to match the Create.vue layout (repo search, branch autocomplete, etc.)
- **Persistent User Configuration** -- Allow users to configure and persist settings per project (and per any scope that makes sense): auto-generation preferences, build triggers, route discovery mode, default visibility, notification preferences, etc. Store as project-level and user-level config with sensible defaults and override hierarchy.

## Competitive Context

| Feature | Laragen | Scramble | Scribe |
|---------|---------|----------|--------|
| Zero annotations | Yes | Yes | No |
| OpenAPI 3.2 | Yes | 3.1 | 3.1 |
| Open source | Yes | Yes | Yes |
| Hosted docs | Planned | No | No |
| Webhook auto-sync | Planned | No | No |

**Key Differentiators**: Webhook auto-sync, open-source core with SaaS layer, agency-friendly pricing, changelog detection.
