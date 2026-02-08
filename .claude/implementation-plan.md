# Implementation Plan: Laragen Features F1-F6

## Context

The Laragen package has 6 planned P0 features for zero-config OpenAPI generation. Significant infrastructure already exists (RuleToSchema, RuleExtractor, RouteSpecCollector, ExampleGenerator). We keep the Scribe dependency (per updated D14) but wrap it behind clear boundaries. Features are implemented in dependency order with TDD.

## Implementation Order

```
F6 (Auth) → F1 (Route Discovery) → F2 (Path Params) → F3 (FormRequest) → F5 (Model Schema) → F4 (JsonResource)
```

---

## Feature F6: Authentication Detection

**Goal**: Detect auth middleware on routes and generate SecurityScheme components + per-operation security.

### New Files

| File | Purpose |
|------|---------|
| `laragen/Auth/AuthDetector.php` | Analyzes route middleware, returns `AuthScheme` value objects |
| `laragen/Auth/AuthScheme.php` | Value object: type (bearer/basic/apiKey), guard name, description |
| `laragen/Auth/SecuritySchemeRegistry.php` | Collects unique security schemes across all routes, produces SecurityScheme objects |
| `tests/Laragen/Unit/Auth/AuthDetectorTest.php` | Tests for middleware pattern detection |
| `tests/Laragen/Unit/Auth/AuthSchemeTest.php` | Tests for value object + SecurityScheme conversion |
| `tests/Laragen/Feature/Auth/AuthDetectionIntegrationTest.php` | End-to-end with real routes |

### Middleware → SecurityScheme Mapping

| Middleware | SecurityScheme |
|-----------|---------------|
| `auth:sanctum` | `SecurityScheme::http(Http::bearer())` |
| `auth:api` (Passport) | `SecurityScheme::http(Http::bearer())` |
| `auth.basic` | `SecurityScheme::http(Http::basic())` |
| `auth:*` (generic) | `SecurityScheme::http(Http::bearer())` with guard name |

### Integration

Modify `Laragen::generate()` to:
1. For each operation, detect auth via `AuthDetector::detect($route)`
2. If detected and operation has no security yet, apply `Security` to the operation
3. Register detected scheme in `SecuritySchemeRegistry`
4. After all paths processed, merge security schemes into `Components`

### Config Addition

```php
// config/laragen.php -> autogen
'security' => env('LARAGEN_AUTOGEN_SECURITY', true),
```

### Key Files to Modify

- `laragen/Laragen.php` — add security augmentation in the operation processing loop
- `config/laragen.php` — add `autogen.security` flag
- `laragen/Providers/LaragenServiceProvider.php` — register new services

---

## Feature F1: Route Discovery (Auto)

**Goal**: Discover API routes by URI patterns instead of requiring `#[Collection]` attributes.

### New Files

| File | Purpose |
|------|---------|
| `laragen/RouteDiscovery/AutoRouteCollector.php` | Collects routes matching include/exclude patterns, returns `Collection<RouteInfo>` |
| `laragen/RouteDiscovery/PatternMatcher.php` | Matches URIs against glob-style patterns |
| `tests/Laragen/Unit/RouteDiscovery/PatternMatcherTest.php` | Pattern matching tests |
| `tests/Laragen/Feature/RouteDiscovery/AutoRouteCollectorTest.php` | Integration tests |

### Design

`AutoRouteCollector` wraps Laravel's `Router`, filters routes by configurable include/exclude patterns, and returns `Collection<RouteInfo>` (same type as existing `RouteCollector`). This ensures all downstream builders work unchanged.

### Config Addition

```php
// config/laragen.php
'route_discovery' => [
    'mode' => 'auto', // 'auto' | 'attribute' | 'combined'
    'include' => ['api/*'],
    'exclude' => ['api/admin/*', 'api/telescope/*', 'api/horizon/*'],
],
```

### Mode Behaviors

- `auto`: Pattern-based only (no attributes needed)
- `attribute`: Existing `RouteCollector` behavior (current default)
- `combined`: Union of both, deduplicated by URI+method

### Key Files to Modify

- `laragen/Laragen.php` — use mode-appropriate collector
- `config/laragen.php` — add route_discovery config section
- `laragen/Providers/LaragenServiceProvider.php` — register `AutoRouteCollector`

---

## Feature F2: Path Parameter Detection (Enhanced)

**Goal**: Enhance existing path parameter detection with route model binding, `whereUuid()`, and constraint detection.

### Existing Code to Enhance

`laragen/Support/RouteSpecCollector.php` already handles:
- `{param}` and `{param?}` detection
- PHP type hint detection (int, bool, string)
- Model binding key type detection (`$model->getKeyType()`)
- Basic `wheres` pattern matching for integers

### New File

| File | Purpose |
|------|---------|
| `laragen/PathParameters/PathParameterAnalyzer.php` | Enhanced path param analysis returning oooapi `Parameter` objects directly |
| `tests/Laragen/Unit/PathParameters/PathParameterAnalyzerTest.php` | Tests for all detection strategies |

### Missing Detection to Add

| Route Constraint | Detection | Schema Result |
|-----------------|-----------|---------------|
| `whereUuid($param)` | Match `[a-f0-9\-]+` or similar UUID regex in `$route->wheres` | `Schema::string()->format('uuid')` |
| `whereAlpha($param)` | Match `[a-zA-Z]+` pattern | `Schema::string()->pattern('[a-zA-Z]+')` |
| `whereAlphaNumeric($param)` | Match `[a-zA-Z0-9]+` pattern | `Schema::string()->pattern('[a-zA-Z0-9]+')` |
| `whereIn($param, [...])` | Array of allowed values | `Schema::string()->enum([...])` — check via route `wheres` regex |

### Integration

Post-process parameters in `Laragen::generate()` — after base spec is built, enhance path parameters with richer type information from `PathParameterAnalyzer`.

---

## Feature F3: FormRequest Extraction (Enhancement)

**Goal**: Enhance existing FormRequest → JSON Schema conversion. Most functionality already exists.

### What Already Works

- `RuleExtractor` (via Scribe) finds FormRequest and inline validation rules
- `RuleToSchema` converts rules to JSON Schema
- `RuleParsers/` handle complex rules (Password, RequiredWithout)
- `Laragen::generate()` already injects request bodies from validation rules

### Gaps to Fill

| Gap | Fix |
|-----|-----|
| `sometimes` rule | Ensure field is NOT in JSON Schema `required` array |
| `nullable` rule | Verify TypeParser adds null type (likely already handled by `riley19280/laravel-rules-to-schema`) |
| Broken `RouteSpecCollector::bodyParams()` | References non-existent `JSONSchemaUtil` — fix or remove |
| Missing rule mappings from spec | Verify all mappings in features.md table are covered |

### New Tests

| File | Purpose |
|------|---------|
| `tests/Laragen/Unit/Support/SometimesRuleTest.php` | Verify `sometimes` keeps field out of `required` |
| `tests/Laragen/Unit/Support/NullableRuleTest.php` | Verify `nullable` adds null type |
| `tests/Laragen/Unit/Support/NestedRulesTest.php` | Verify `user.email`, `items.*.name` produce nested schemas |

### Key Files to Modify

- `laragen/Support/RouteSpecCollector.php` — fix broken `bodyParams()` method
- `laragen/Support/RuleToSchema.php` — add `sometimes` handling if missing

---

## Feature F5: Model Schema Inference

**Goal**: Generate JSON Schema from Eloquent model definitions.

### New Files

| File | Purpose |
|------|---------|
| `laragen/ModelSchema/ModelSchemaInferrer.php` | Main: model class-string → JSON Schema |
| `laragen/ModelSchema/CastAnalyzer.php` | Reads `$casts`, maps to JSON Schema types |
| `laragen/ModelSchema/MigrationAnalyzer.php` | Parses migrations for column types (uses `nikic/php-parser`) |
| `laragen/ModelSchema/ColumnTypeMapper.php` | Maps DB column types and cast types to JSON Schema |
| `tests/Laragen/Unit/ModelSchema/CastAnalyzerTest.php` | All cast type mappings |
| `tests/Laragen/Unit/ModelSchema/MigrationAnalyzerTest.php` | Migration parsing |
| `tests/Laragen/Unit/ModelSchema/ModelSchemaInferrerTest.php` | Full model inference |
| `tests/Laragen/Support/Doubles/Models/` | Stub models for testing |

### Schema Inference Priority

1. `$casts` (primary — always available at runtime)
2. Migration column types (secondary — fill gaps for non-cast fields)
3. `$appends` accessors (add with string fallback type)
4. Remove `$hidden` fields from schema

### Cast → Schema Mapping

| Laravel Cast | JSON Schema |
|-------------|-------------|
| `int`, `integer` | `Schema::integer()` |
| `float`, `double`, `real` | `Schema::number()` |
| `string` | `Schema::string()` |
| `bool`, `boolean` | `Schema::boolean()` |
| `array`, `collection`, `object` | `Schema::object()` |
| `date`, `datetime`, `immutable_date`, `immutable_datetime` | `Schema::string()->format('date-time')` |
| `timestamp` | `Schema::integer()` |
| `decimal:N` | `Schema::string()` |
| Backed enum | `Schema::enum(...)` from enum values |

### Circular Reference Handling

Track in-progress models. If model A references model B which references model A, return a `$ref` to `#/components/schemas/ModelName` for the cycle.

---

## Feature F4: JsonResource Detection

**Goal**: Analyze JsonResource classes to auto-generate response schemas.

### New Files

| File | Purpose |
|------|---------|
| `laragen/ResponseSchema/ResponseDetector.php` | Detects JsonResource return type from controller methods |
| `laragen/ResponseSchema/JsonResourceAnalyzer.php` | Parses `toArray()` method body using `nikic/php-parser` AST |
| `laragen/ResponseSchema/ResourceFieldExtractor.php` | Extracts fields from AST nodes |
| `laragen/ResponseSchema/FieldType.php` | Value object for extracted field type info |
| `laragen/ResponseSchema/ResponseSchemaBuilder.php` | Builds oooapi Response objects from detected info |
| `tests/Laragen/Unit/ResponseSchema/ResponseDetectorTest.php` | Return type + AST detection |
| `tests/Laragen/Unit/ResponseSchema/JsonResourceAnalyzerTest.php` | AST pattern parsing |
| `tests/Laragen/Unit/ResponseSchema/ResponseSchemaBuilderTest.php` | Response building |
| `tests/Laragen/Support/Doubles/Resources/` | Stub JsonResource classes |

### AST Patterns to Handle

| Pattern | Handling |
|---------|----------|
| `'name' => $this->name` | Look up type from model schema (F5) |
| `'type' => 'user'` | Const/literal type |
| `$this->whenLoaded('posts', ...)` | Optional relationship, recursive resource analysis |
| `$this->when($cond, $val)` | Optional/conditional field |
| `SomeResource::collection(...)` | Array of resource item schema |
| `$this->merge([...])` | Flatten merged fields into parent |

### Response Wrapping

Respect `JsonResource::$wrap` property:
- Default `data` → wrap in `{"data": {...}}`
- `$wrap = null` → no wrapping
- Custom string → use that key

### Config Addition

```php
// config/laragen.php -> autogen
'response' => env('LARAGEN_AUTOGEN_RESPONSE', true),
```

### Graceful Degradation (D11)

Unrecognized AST patterns fall back to `Schema::string()` with a logged warning. Never fail entire generation due to unparseable expression.

---

## Laragen.php Refactoring (Done Incrementally)

Convert from static utility to instance-based with DI as features are added:

```php
final readonly class Laragen
{
    public function __construct(
        private AuthDetector $authDetector,           // F6
        private SecuritySchemeRegistry $securityRegistry, // F6
        private ResponseDetector $responseDetector,   // F4
        private ResponseSchemaBuilder $responseSchemaBuilder, // F4
        // ... existing deps
    ) {}

    public function generate(string $collection): OpenAPI { ... }
}
```

Registered as singleton in `LaragenServiceProvider`.

---

## Verification Plan

For each feature:
1. Write tests first (TDD)
2. Implement code
3. `composer test` — all tests pass
4. `composer fixer` — code style
5. `composer lint` — static analysis

End-to-end validation: Create test routes in `workbench/` exercising all features, run `Laragen::generate()`, verify output spec contains expected security schemes, path parameters, request bodies, response schemas.

---

## Pre-Implementation Checklist (Completed)

- [x] `.claude/decisions.md` — Updated D14 from "Study, Don't Depend" to "Leverage Open-Source, Maintain Clear Boundaries"
- [x] `.claude/features.md` — Added implementation status, order, and details for each feature
- [x] `.claude/planned-features.md` — Added full implementation details (file structure, integration points)
- [x] `.claude/platform-decisions.md` — Updated LD3 reference to align with D14 update
- [x] `.claude/implementation-plan.md` — This file (full standalone plan for reference)
