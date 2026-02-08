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

- **RuleParsers**: Convert Laravel validation rules to JSON Schema (`PasswordParser`, `RequiredWithoutParser`, `ExampleOverride`)
- **OpenAPIGenerator**: Extended generator with auto-detection
- **Builders**: Enhanced builders for automatic schema inference
- **ExampleGenerator**: Generate example values from schemas
- Configuration via `config/laragen.php` and `config/rules-to-schema.php`

---

## Planned Features (Laragen Package)

### F1: Route Discovery (Auto)
**Priority**: P0

Automatically discover API routes from Laravel's router without annotations.

**Requirements**:
- Discover routes from Laravel router
- Filter by configurable include/exclude patterns
- Default: include `api/*`, exclude common admin routes
- Handle resource routes and API resource routes
- Extract HTTP method(s), URI, controller, middleware

---

### F2: Path Parameter Detection (Auto)
**Priority**: P0

Extract and type path parameters from route definitions automatically.

**Requirements**:
- Parse `{param}` from URI → required path parameter
- Parse `{param?}` → optional parameter
- Detect type from route model binding
- Detect type from `whereNumber()`, `whereUuid()` constraints
- Fall back to string if type unknown

---

### F3: FormRequest Extraction (Auto)
**Priority**: P0

Convert Laravel FormRequest validation rules to OpenAPI request body schema automatically.

**Requirements**:
- Detect FormRequest from controller method signature
- Extract `rules()` array
- Map Laravel rules to JSON Schema (see validation rule mapping)
- Handle nested rules (`user.email`, `items.*.name`)
- Respect `required` vs `sometimes`
- Handle `nullable`

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
| `min:N` (string) | `minLength: N` |
| `max:N` (string) | `maxLength: N` |
| `min:N` (number) | `minimum: N` |
| `max:N` (number) | `maximum: N` |
| `in:a,b,c` | `enum: ["a", "b", "c"]` |
| `regex:/pattern/` | `pattern: "pattern"` |
| `nullable` | Wrapped with null in oneOf |

---

### F4: JsonResource Detection (Auto)
**Priority**: P0

Analyze JsonResource classes to generate response schemas automatically.

**Requirements**:
- Detect JsonResource return type from controller
- Parse `toArray()` method body using AST
- Infer types from `$this->field` access
- Handle `whenLoaded()` for relationships
- Handle `when()` for conditional fields
- Support resource collections

---

### F5: Model Schema Inference (Auto)
**Priority**: P0

Infer JSON Schema from Eloquent model definitions.

**Requirements**:
- Parse model's `$casts` array for type hints
- Analyze migration files for column types
- Respect `$hidden` (exclude from schema)
- Include `$appends` (custom accessors)
- Handle relationships (as $ref or inline)

---

### F6: Authentication Detection (Auto)
**Priority**: P0

Detect authentication requirements from route middleware.

**Requirements**:
- Detect `auth:sanctum` → Bearer token
- Detect `auth:api` (Passport) → Bearer token
- Detect `auth.basic` → HTTP Basic
- Generate appropriate security schemes
- Apply security to operations

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

## Feature Priority Matrix

| Feature | Package | Priority | Dependencies |
|---------|---------|----------|--------------|
| F1: Route Discovery | laragen | P0 | - |
| F2: Path Parameters | laragen | P0 | F1 |
| F3: FormRequest | laragen | P0 | F1 |
| F4: JsonResource | laragen | P0 | F1 |
| F5: Model Schema | laragen | P0 | - |
| F6: Auth Detection | laragen | P0 | F1 |
