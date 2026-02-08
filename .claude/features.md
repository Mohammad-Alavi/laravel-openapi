# Features

## What Exists Today

### JSONSchema Package

PHP implementation of JSON Schema Draft 2020-12.

- `Schema` static factory: `Schema::string()`, `Schema::object()`, `Schema::array()`, `Schema::integer()`, etc.
- `Property` for defining object properties
- `BooleanSchema` for true/false schemas
- Keyword system for extensibility
- Format registry for string format validation
- Validation support

### oooapi Package

Object-oriented OpenAPI 3.2 builder with fluent API.

- Full OpenAPI 3.2 object model: `OpenAPI`, `Info`, `Server`, `PathItem`, `Operation`, `Parameter`, `Header`, `Response`, `RequestBody`, `MediaType`, `Schema`, `Components`, `Encoding`, `Discriminator`, etc.
- Immutable objects with `create()` factories and fluent setters
- `compile()` for recursive serialization to arrays
- Extension support (`x-*` fields) via `ExtensibleObject`
- Parameter locations: `path`, `query`, `header`, `cookie`, `querystring`
- Serialization rules per location: `HeaderParameter`, `QueryParameter`, `PathParameter`, `CookieParameter`
- Content-based serialization via `Content` with `ContentEntry`
- Style system: `Simple`, `Form`, `DeepObject`, `Label`, `Matrix`, `PipeDelimited`, `SpaceDelimited`, `Cookie`
- Examples support (singular `example` and plural `examples` with `ExampleEntry`)
- `$ref` support via reference objects
- `ShouldBeReferenced` interface for components

### LaravelOpenApi Package (src/)

Laravel integration layer for generating OpenAPI from annotated controllers.

- **Generator** (`Generator.php`): Main orchestrator
- **Builders**: `PathBuilder`, `OperationBuilder`, `ParametersBuilder`, `RequestBodyBuilder`, `ResponsesBuilder`, `CallbacksBuilder`, `ComponentsBuilder`
- **PHP 8 Attributes**: `#[Collection]`, `#[Operation]`, `#[PathItem]` for annotating controllers
- **Factory pattern**: User-defined factories for schemas, parameters, responses, request bodies, callbacks, security schemes
- **Collections**: Group related API documentation
- **Service Provider**: `OpenApiServiceProvider` for Laravel registration
- **Artisan Commands**: CLI commands for generation
- **Workbench**: Test application under `workbench/` with sample controllers, factories, and Petstore example

### Laragen Package

SAAS product layer for zero-config OpenAPI generation.

- **Auth Detection** (`laragen/Auth/`): Auto-detect authentication from route middleware → SecurityScheme components
- **Route Discovery** (`laragen/RouteDiscovery/`): Auto-discover API routes by URI patterns (no annotations needed)
- **Path Parameters** (`laragen/PathParameters/`): Detect path parameter types from route constraints (`whereUuid`, `whereAlpha`, etc.)
- **FormRequest Extraction** (`laragen/Support/`): Convert validation rules to JSON Schema request bodies (via Scribe + laravel-rules-to-schema)
- **Model Schema** (`laragen/ModelSchema/`): Infer JSON Schema from Eloquent `$casts`, `$hidden`, `$appends`
- **Response Schema** (`laragen/ResponseSchema/`): Analyze JsonResource `toArray()` AST to generate response schemas
- **RuleParsers** (`laragen/RuleParsers/`): Custom parsers for complex validation rules (`PasswordParser`, `RequiredWithoutParser`, `ExampleOverride`)
- **ExampleGenerator** (`laragen/ExampleGenerator/`): Generate example values from schemas
- Configuration via `config/laragen.php` and `config/rules-to-schema.php`

---

## Implemented Features (Laragen Package)

All P0 features are implemented. Implementation order was:

```
F6 (Auth) → F1 (Route Discovery) → F2 (Path Params) → F3 (FormRequest) → F5 (Model Schema) → F4 (JsonResource)
```

---

### F6: Authentication Detection
**Status**: Implemented

Detects auth middleware on routes and generates SecurityScheme components + per-operation security.

**Files**: `laragen/Auth/AuthDetector.php`, `AuthScheme.php`, `SecuritySchemeRegistry.php`
**Config**: `autogen.security` flag in `config/laragen.php`

| Middleware | SecurityScheme |
|-----------|---------------|
| `auth:sanctum` | Bearer token |
| `auth:api` (Passport) | Bearer token |
| `auth.basic` | HTTP Basic |
| `auth:*` (generic) | Bearer token with guard name |

---

### F1: Route Discovery (Auto)
**Status**: Implemented

Discovers API routes by URI patterns without requiring `#[Collection]` attributes.

**Files**: `laragen/RouteDiscovery/AutoRouteCollector.php`, `PatternMatcher.php`
**Config**: `route_discovery.mode` (`auto` | `attribute` | `combined`), `include`/`exclude` patterns

---

### F2: Path Parameter Detection
**Status**: Implemented

Detects path parameter types from route constraints and generates typed `Parameter` objects.

**Files**: `laragen/PathParameters/PathParameterAnalyzer.php`
**Config**: `autogen.path_parameters` flag

| Route Constraint | Schema Result |
|-----------------|---------------|
| `whereUuid()` | `string` + `format: uuid` |
| `whereAlpha()` | `string` + `pattern: [a-zA-Z]+` |
| `whereAlphaNumeric()` | `string` + `pattern: [a-zA-Z0-9]+` |
| `whereNumber()` | `integer` |
| `whereUlid()` | `string` + ULID pattern |
| `whereIn()` | `enum` with extracted values |

---

### F3: FormRequest Extraction
**Status**: Implemented

Converts Laravel FormRequest validation rules to OpenAPI request body schemas. Uses Scribe's `RuleExtractor` and `riley19280/laravel-rules-to-schema`.

**Key findings during implementation**: All 17 rule mappings were already handled by the vendor package. `sometimes` and `nullable` work correctly. Broken `RouteSpecCollector::bodyParams()` dead code was removed.

**Validation Rule Mapping**:

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

---

### F5: Model Schema Inference
**Status**: Implemented

Generates JSON Schema from Eloquent model `$casts`, excludes `$hidden`, includes `$appends`.

**Files**: `laragen/ModelSchema/ModelSchemaInferrer.php`, `CastAnalyzer.php`

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

---

### F4: JsonResource Detection
**Status**: Implemented

Analyzes controller return types and JsonResource `toArray()` AST to generate response schemas.

**Files**: `laragen/ResponseSchema/ResponseDetector.php`, `JsonResourceAnalyzer.php`, `ResourceField.php`, `ResponseSchemaBuilder.php`
**Config**: `autogen.response` flag

**AST Patterns Handled**:
| Pattern | Result |
|---------|--------|
| `'key' => $this->prop` | Model property → `string` type |
| `'key' => 'literal'` | Literal → `enum` with const value |
| `new Resource($this->whenLoaded(...))` | Nested resource → recursive schema |
| `$this->when(...)` / `$this->whenLoaded(...)` | Conditional → `string` fallback |

**Response Wrapping**: Respects `JsonResource::$wrap` (default `data`, `null` = no wrap)

---

## Planned Platform Features (Future SaaS)

### P1: GitHub OAuth Connection
Connect GitHub repositories to auto-generate documentation.

### P2: Webhook Processing
Automatically rebuild docs on push events.

### P3: Containerized Analysis
Run analysis safely in isolated Docker containers.

### P4: Hosted Documentation
Serve interactive documentation with Stoplight Elements.

### P5: Custom Domains
Allow users to use their own domains for hosted docs.

### P6: Changelog Generation
Automatically detect and document API changes between versions.

### P7: Breaking Change Detection
Alert users when API changes might break clients.

### P8: Team Collaboration
Multiple users per organization with role-based access.

### P9: Billing
Stripe integration for subscription management.

---

## Feature Status Matrix

| Feature | Package | Status | Commit |
|---------|---------|--------|--------|
| F6: Auth Detection | laragen | Implemented | `aa4be104` |
| F1: Route Discovery | laragen | Implemented | `a92fdbf3` |
| F2: Path Parameters | laragen | Implemented | `62028e49` |
| F3: FormRequest | laragen | Implemented | `a7e65d53` |
| F5: Model Schema | laragen | Implemented | `b8af7912` |
| F4: JsonResource | laragen | Implemented | `65a3e419` |
