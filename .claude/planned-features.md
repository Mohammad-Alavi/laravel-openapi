# Laragen Features — Implemented

All P0 features are implemented and integrated into `Laragen::enrichSpec()`.

## Directory Structure

```
laragen/
├── Auth/                  # F6: Authentication detection
│   ├── AuthDetector.php
│   ├── AuthScheme.php
│   └── SecuritySchemeRegistry.php
├── Builders/              # Enhanced builders
├── Console/               # Artisan commands
├── ExampleGenerator/      # Example value generation
├── ModelSchema/           # F5: Model schema inference
│   ├── CastAnalyzer.php
│   └── ModelSchemaInferrer.php
├── PathParameters/        # F2: Path parameter detection
│   └── PathParameterAnalyzer.php
├── Providers/             # Service providers
├── ArraySchema/           # Generic array-return AST analysis
│   ├── ArrayField.php
│   └── ArraySchemaAnalyzer.php
├── ResponseSchema/        # F4: Response schema strategies
│   └── JsonResource/
│       ├── JsonResourceDetector.php
│       ├── JsonResourceModelDetector.php
│       └── JsonResourceSchemaBuilder.php
├── RouteDiscovery/        # F1: Route discovery
│   ├── AutoRouteCollector.php
│   └── PatternMatcher.php
├── RuleParsers/           # F3: Custom rule parsers
│   ├── ExampleOverride.php
│   ├── PasswordParser.php
│   └── RequiredWithoutParser.php
├── Support/               # Shared utilities
│   ├── Config/
│   ├── RouteSpecCollector.php
│   └── RuleToSchema.php
├── Laragen.php            # Main orchestrator
└── OpenAPIGenerator.php   # Extended generator
```

## Configuration

`config/laragen.php`:
```php
[
    'route_discovery' => [
        'mode' => 'attribute',  // 'auto' | 'attribute' | 'combined'
        'include' => ['api/*'],
        'exclude' => ['api/admin/*', 'api/telescope/*', 'api/horizon/*'],
    ],
    'autogen' => [
        'request_body' => true,
        'example' => true,
        'security' => true,
        'path_parameters' => true,
        'response' => true,
    ],
]
```

## Architecture

`Laragen::generate()` follows a two-phase approach:
1. **`buildBaseSpec()`**: Builds the base OpenAPI spec using route discovery mode (attribute, auto, or combined)
2. **`enrichSpec()`**: Post-processes the spec to add request bodies, security, path parameters, and response schemas

All enrichment services are resolved via Laravel's service container in `enrichSpec()`:
- `AuthDetector` + `SecuritySchemeRegistry` (F6)
- `PathParameterAnalyzer` (F2)
- `JsonResourceDetector` + `JsonResourceSchemaBuilder` (F4)

Each enrichment is controlled by its `autogen.*` config flag and only applies when the operation doesn't already have that data (no overwriting user-defined specs)

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
| Zero annotations | Yes | Yes | No |
| OpenAPI 3.2 | Yes | 3.1 | 3.1 |
| Open source | Yes | Yes | Yes |
| Hosted docs | Planned | No | No |
| Webhook auto-sync | Planned | No | No |

**Key Differentiators**:
1. Webhook auto-sync (no competitor has this)
2. Open-source core with SaaS layer
3. Agency-friendly pricing planned
4. Changelog detection planned
