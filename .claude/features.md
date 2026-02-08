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

- **Auth Detection** (`laragen/Auth/`): Auto-detect authentication from route middleware -> SecurityScheme components
- **Route Discovery** (`laragen/RouteDiscovery/`): Auto-discover API routes by URI patterns (no annotations needed)
- **Path Parameters** (`laragen/PathParameters/`): Detect path parameter types from route constraints (`whereUuid`, `whereAlpha`, etc.)
- **FormRequest Extraction** (`laragen/Support/`): Convert validation rules to JSON Schema request bodies (via Scribe + laravel-rules-to-schema)
- **Model Schema** (`laragen/ModelSchema/`): Infer JSON Schema from Eloquent `$casts`, `$hidden`, `$appends`
- **Response Schema** (`laragen/ResponseSchema/`): Multi-strategy response detection via pluggable `ResponseStrategy` chain (JsonResource, FractalTransformer, EloquentModel)
- **RuleParsers** (`laragen/RuleParsers/`): Custom parsers for complex validation rules (`PasswordParser`, `RequiredWithoutParser`, `ExampleOverride`)
- **ExampleGenerator** (`laragen/ExampleGenerator/`): Generate example values from schemas
- Configuration via `config/laragen.php` and `config/rules-to-schema.php`

---

## Laragen Feature Details

### F6: Authentication Detection

Detects auth middleware on routes and generates SecurityScheme components + per-operation security.

**Files**: `laragen/Auth/AuthDetector.php`, `AuthScheme.php`, `SecuritySchemeRegistry.php`
**Config**: `autogen.security` flag

| Middleware | SecurityScheme |
|-----------|---------------|
| `auth:sanctum` | Bearer token |
| `auth:api` (Passport) | Bearer token |
| `auth.basic` | HTTP Basic |
| `auth:*` (generic) | Bearer token with guard name |

---

### F1: Route Discovery (Auto)

Discovers API routes by URI patterns without requiring `#[Collection]` attributes.

**Files**: `laragen/RouteDiscovery/AutoRouteCollector.php`, `PatternMatcher.php`
**Config**: `route_discovery.mode` (`auto` | `attribute` | `combined`), `include`/`exclude` patterns

---

### F2: Path Parameter Detection

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

Converts Laravel FormRequest validation rules to OpenAPI request body schemas. Uses Scribe's `RuleExtractor` and `riley19280/laravel-rules-to-schema`.

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

### F4: Response Schema Detection (Multi-Strategy)

Pluggable `ResponseStrategy` chain analyzes controller return types to auto-generate response schemas.

**Strategy chain** (tried in order, first match wins):
1. **JsonResource** -- detects `JsonResource` return type, analyzes `toArray()` AST
2. **FractalTransformer** -- conditional on `league/fractal`, detects transformer references in controller AST
3. **EloquentModel** -- detects `Model` return type, delegates to `ModelSchemaInferrer`

**Architecture**: Generic AST analysis lives in `ArraySchemaAnalyzer` (reusable across strategies). Each strategy has a `ResponseDetector` (finds the response class) and `ResponseSchemaBuilder` (builds JSON Schema from it). `ResponseSchemaResolver` iterates the chain.

**Key files**:
- `laragen/ArraySchema/ArraySchemaAnalyzer.php` -- generic AST analysis (21 patterns)
- `laragen/ArraySchema/ArrayField.php` -- field value object (8 factory methods)
- `laragen/ResponseSchema/ResponseSchemaResolver.php` -- strategy chain
- `laragen/ResponseSchema/JsonResource/` -- JsonResource strategy
- `laragen/ResponseSchema/EloquentModel/` -- Eloquent Model strategy
- `laragen/ResponseSchema/FractalTransformer/` -- Fractal strategy (conditional)

**AST Patterns** (21): model property, string/int/float/bool/null literals, method chains, explicit resource access, nested resources, collections, 12 conditional methods, merge/mergeWhen/mergeUnless, null coalescing, nullsafe property/method, type casting, nested arrays, function calls, class constants, concat, arithmetic, boolean NOT, comparisons.

**Config**: `autogen.response` flag. Respects `JsonResource::$wrap` property.

---

## Planned Platform Features (Future SaaS)

- **GitHub OAuth** -- Connect repos to auto-generate docs
- **Webhook Processing** -- Auto-rebuild on push events
- **Containerized Analysis** -- Isolated Docker containers for user code
- **Hosted Documentation** -- Interactive docs with Stoplight Elements
- **Custom Domains** -- User's own domain for hosted docs
- **Changelog Generation** -- Detect API changes between versions
- **Breaking Change Detection** -- Alert on breaking API changes
- **Team Collaboration** -- Org-based role access
- **Billing** -- Stripe subscriptions

## Competitive Context

| Feature | Laragen | Scramble | Scribe |
|---------|---------|----------|--------|
| Zero annotations | Yes | Yes | No |
| OpenAPI 3.2 | Yes | 3.1 | 3.1 |
| Open source | Yes | Yes | Yes |
| Hosted docs | Planned | No | No |
| Webhook auto-sync | Planned | No | No |

**Key Differentiators**: Webhook auto-sync, open-source core with SaaS layer, agency-friendly pricing, changelog detection.
