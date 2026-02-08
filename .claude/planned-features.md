# Laragen Features

## Current Features

### RuleParsers

Convert Laravel validation rules to JSON Schema. Located in `laragen/RuleParsers/`.

- **PasswordParser** — Converts Laravel password validation rules to schema constraints
- **RequiredWithoutParser** — Handles `required_without` conditional requirements
- **ExampleOverride** — Allows overriding auto-generated examples

### OpenAPIGenerator

Extended generator (`laragen/OpenAPIGenerator.php`) that builds on the LaravelOpenApi `Generator` with auto-detection capabilities for zero-config usage.

### Enhanced Builders

Located in `laragen/Builders/`. Extend the base LaravelOpenApi builders with automatic schema inference from:
- Controller method signatures
- Form request validation rules
- Eloquent model definitions
- Route definitions

### ExampleGenerator

Located in `laragen/ExampleGenerator/`. Generates realistic example values from JSON Schema definitions.

### Configuration

- `config/laragen.php` — Main Laragen settings
- `config/rules-to-schema.php` — Mapping of Laravel validation rules to JSON Schema types

---

## Planned Features — Implementation Details

### Implementation Order

```
F6 (Auth) → F1 (Route Discovery) → F2 (Path Params) → F3 (FormRequest) → F5 (Model Schema) → F4 (JsonResource)
```

---

### F6: Authentication Detection (1st — no dependencies)

**New Files**:
| File | Purpose |
|------|---------|
| `laragen/Auth/AuthDetector.php` | Analyzes route middleware, returns `AuthScheme` value objects |
| `laragen/Auth/AuthScheme.php` | Value object: type (bearer/basic/apiKey), guard name, description |
| `laragen/Auth/SecuritySchemeRegistry.php` | Collects unique security schemes across all routes, produces SecurityScheme objects |
| `tests/Laragen/Unit/Auth/AuthDetectorTest.php` | Tests for middleware pattern detection |
| `tests/Laragen/Unit/Auth/AuthSchemeTest.php` | Tests for value object + SecurityScheme conversion |
| `tests/Laragen/Feature/Auth/AuthDetectionIntegrationTest.php` | End-to-end with real routes |

**Middleware → SecurityScheme Mapping**:
| Middleware | SecurityScheme |
|-----------|---------------|
| `auth:sanctum` | `SecurityScheme::http(Http::bearer())` |
| `auth:api` (Passport) | `SecurityScheme::http(Http::bearer())` |
| `auth.basic` | `SecurityScheme::http(Http::basic())` |
| `auth:*` (generic) | `SecurityScheme::http(Http::bearer())` with guard name |

**Integration Points**:
- `laragen/Laragen.php` — add security augmentation in operation processing loop
- `config/laragen.php` — add `autogen.security` flag
- `laragen/Providers/LaragenServiceProvider.php` — register new services

---

### F1: Route Discovery (2nd)

**New Files**:
| File | Purpose |
|------|---------|
| `laragen/RouteDiscovery/AutoRouteCollector.php` | Collects routes matching include/exclude patterns, returns `Collection<RouteInfo>` |
| `laragen/RouteDiscovery/PatternMatcher.php` | Matches URIs against glob-style patterns |
| `tests/Laragen/Unit/RouteDiscovery/PatternMatcherTest.php` | Pattern matching tests |
| `tests/Laragen/Feature/RouteDiscovery/AutoRouteCollectorTest.php` | Integration tests |

**Config Addition**:
```php
'route_discovery' => [
    'mode' => 'auto',  // 'auto' | 'attribute' | 'combined'
    'include' => ['api/*'],
    'exclude' => ['api/admin/*', 'api/telescope/*', 'api/horizon/*'],
],
```

**Mode Behaviors**:
- `auto`: Pattern-based only (no attributes needed)
- `attribute`: Existing `RouteCollector` behavior (current default)
- `combined`: Union of both, deduplicated by URI+method

---

### F2: Path Parameter Detection (3rd — depends on F1)

**New Files**:
| File | Purpose |
|------|---------|
| `laragen/PathParameters/PathParameterAnalyzer.php` | Enhanced path param analysis returning oooapi `Parameter` objects |
| `tests/Laragen/Unit/PathParameters/PathParameterAnalyzerTest.php` | Tests for all detection strategies |

**Enhanced Detection** (building on existing `RouteSpecCollector`):
| Route Constraint | Detection | Schema Result |
|-----------------|-----------|---------------|
| `whereUuid($param)` | Match UUID regex in `$route->wheres` | `Schema::string()->format('uuid')` |
| `whereAlpha($param)` | Match `[a-zA-Z]+` pattern | `Schema::string()->pattern('[a-zA-Z]+')` |
| `whereAlphaNumeric($param)` | Match `[a-zA-Z0-9]+` pattern | `Schema::string()->pattern('[a-zA-Z0-9]+')` |
| `whereIn($param, [...])` | Array of allowed values | `Schema::string()->enum([...])` |

---

### F3: FormRequest Extraction (4th — partially implemented)

**What Already Works**: `RuleExtractor` (via Scribe), `RuleToSchema`, `RuleParsers/`, injection in `Laragen::generate()`

**Gaps to Fill**:
| Gap | Fix |
|-----|-----|
| `sometimes` rule | Ensure field NOT in JSON Schema `required` array |
| `nullable` rule | Verify TypeParser adds null type |
| Broken `RouteSpecCollector::bodyParams()` | References non-existent `JSONSchemaUtil` — fix or remove |
| Missing rule mappings | Verify all mappings in features.md table are covered |

**New Test Files**:
| File | Purpose |
|------|---------|
| `tests/Laragen/Unit/Support/SometimesRuleTest.php` | Verify `sometimes` keeps field out of `required` |
| `tests/Laragen/Unit/Support/NullableRuleTest.php` | Verify `nullable` adds null type |
| `tests/Laragen/Unit/Support/NestedRulesTest.php` | Verify nested rules produce nested schemas |

---

### F5: Model Schema Inference (5th)

**New Files**:
| File | Purpose |
|------|---------|
| `laragen/ModelSchema/ModelSchemaInferrer.php` | Main: model class-string → JSON Schema |
| `laragen/ModelSchema/CastAnalyzer.php` | Reads `$casts`, maps to JSON Schema types |
| `laragen/ModelSchema/MigrationAnalyzer.php` | Parses migrations for column types (nikic/php-parser) |
| `laragen/ModelSchema/ColumnTypeMapper.php` | Maps DB column/cast types to JSON Schema |
| `tests/Laragen/Unit/ModelSchema/CastAnalyzerTest.php` | All cast type mappings |
| `tests/Laragen/Unit/ModelSchema/MigrationAnalyzerTest.php` | Migration parsing |
| `tests/Laragen/Unit/ModelSchema/ModelSchemaInferrerTest.php` | Full model inference |
| `tests/Laragen/Support/Doubles/Models/` | Stub models for testing |

**Schema Inference Priority**:
1. `$casts` (primary — always available at runtime)
2. Migration column types (secondary — fill gaps for non-cast fields)
3. `$appends` accessors (add with string fallback type)
4. Remove `$hidden` fields from schema

**Cast → Schema Mapping**:
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

**Circular Reference Handling**: Track in-progress models. If cycle detected, return `$ref` to `#/components/schemas/ModelName`.

---

### F4: JsonResource Detection (6th — depends on F1, F5)

**New Files**:
| File | Purpose |
|------|---------|
| `laragen/ResponseSchema/ResponseDetector.php` | Detects JsonResource return type from controller methods |
| `laragen/ResponseSchema/JsonResourceAnalyzer.php` | Parses `toArray()` body using nikic/php-parser AST |
| `laragen/ResponseSchema/ResourceFieldExtractor.php` | Extracts fields from AST nodes |
| `laragen/ResponseSchema/FieldType.php` | Value object for extracted field type info |
| `laragen/ResponseSchema/ResponseSchemaBuilder.php` | Builds oooapi Response objects from detected info |
| `tests/Laragen/Unit/ResponseSchema/ResponseDetectorTest.php` | Return type + AST detection |
| `tests/Laragen/Unit/ResponseSchema/JsonResourceAnalyzerTest.php` | AST pattern parsing |
| `tests/Laragen/Unit/ResponseSchema/ResponseSchemaBuilderTest.php` | Response building |
| `tests/Laragen/Support/Doubles/Resources/` | Stub JsonResource classes |

**AST Patterns to Handle**:
| Pattern | Handling |
|---------|----------|
| `'name' => $this->name` | Look up type from model schema (F5) |
| `'type' => 'user'` | Const/literal type |
| `$this->whenLoaded('posts', ...)` | Optional relationship, recursive resource analysis |
| `$this->when($cond, $val)` | Optional/conditional field |
| `SomeResource::collection(...)` | Array of resource item schema |
| `$this->merge([...])` | Flatten merged fields into parent |

**Response Wrapping**: Respect `JsonResource::$wrap` property (default `data`, `null` = no wrap, custom string = that key).

**Config Addition**: `autogen.response` flag in `config/laragen.php`

**Graceful Degradation (D11)**: Unrecognized AST patterns fall back to `Schema::string()` with logged warning.

---

### Laragen.php Refactoring (Incremental)

Convert from static utility to instance-based with DI as features are added:

```php
final readonly class Laragen
{
    public function __construct(
        private AuthDetector $authDetector,               // F6
        private SecuritySchemeRegistry $securityRegistry,  // F6
        private ResponseDetector $responseDetector,        // F4
        private ResponseSchemaBuilder $responseSchemaBuilder, // F4
        // ... existing deps
    ) {}

    public function generate(string $collection): OpenAPI { ... }
}
```

Registered as singleton in `LaragenServiceProvider`.

---

## Planned Platform Features (Future SaaS)

- **GitHub OAuth** — Connect repos to auto-generate docs
- **Webhook Processing** — Auto-rebuild on push events
- **Containerized Analysis** — Isolated Docker containers for user code
- **Hosted Documentation** — Interactive docs with Stoplight Elements
- **Custom Domains** — User's own domain for hosted docs
- **Changelog Generation** — Detect API changes between versions
- **Breaking Change Detection** — Alert on breaking API changes
- **Team Collaboration** — Org-based role access
- **Billing** — Stripe subscriptions

---

## Competitive Context

| Feature | Laragen | Scramble | Scribe |
|---------|---------|----------|--------|
| Zero annotations | Planned | Yes | No |
| OpenAPI 3.2 | Yes | 3.1 | 3.1 |
| Open source | Yes | Yes | Yes |
| Hosted docs | Planned | No | No |
| Webhook auto-sync | Planned | No | No |

**Key Differentiators**:
1. Webhook auto-sync (no competitor has this)
2. Open-source core with SaaS layer
3. Agency-friendly pricing planned
4. Changelog detection planned
