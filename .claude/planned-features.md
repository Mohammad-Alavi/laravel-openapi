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

## Planned Features

### Route Discovery (Auto)
Automatically discover API routes from Laravel's router without annotations.
- Discover routes, filter by include/exclude patterns
- Handle resource routes, API resource routes
- Extract HTTP method(s), URI, controller, middleware

### Path Parameter Detection (Auto)
Extract and type path parameters from route definitions.
- Parse `{param}` and `{param?}` from URIs
- Detect type from route model binding and constraints
- Fall back to string if unknown

### FormRequest Extraction (Auto)
Convert Laravel FormRequest validation rules to OpenAPI request body schema.
- Detect FormRequest from controller method signature
- Map Laravel rules to JSON Schema
- Handle nested rules (`user.email`, `items.*.name`)

### JsonResource Detection (Auto)
Analyze JsonResource classes to generate response schemas.
- Parse `toArray()` method body using AST
- Handle `whenLoaded()`, `when()`, `merge()`
- Support resource collections

### Model Schema Inference (Auto)
Infer JSON Schema from Eloquent model definitions.
- Use `$casts`, migration column types, `$hidden`, `$appends`

### Authentication Detection (Auto)
Detect auth requirements from middleware.
- Detect sanctum, passport, basic auth
- Generate security schemes

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
