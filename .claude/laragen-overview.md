# Laragen Project Knowledge

## What is Laragen?

Laragen (`MohammadAlavi\Laragen`) is the top-level package in the dependency chain. It provides a SAAS product for 1-click OpenAPI generation from Laravel repositories with minimal configuration.

## Position in Dependency Chain

```
JSONSchema -> oooapi -> src (LaravelOpenApi) -> laragen
```

Laragen depends on all three lower packages. It extends the LaravelOpenApi package with automatic analysis capabilities that require zero manual annotation.

## Directory Structure

```
laragen/
├── Auth/                  # F6: Auth middleware → SecurityScheme
│   ├── AuthDetector.php
│   ├── AuthScheme.php
│   └── SecuritySchemeRegistry.php
├── Builders/              # Enhanced builders for auto-detection
├── Console/               # Artisan commands
├── ExampleGenerator/      # Generate example values from schemas
├── ModelSchema/           # F5: Eloquent model → JSON Schema
│   ├── CastAnalyzer.php
│   └── ModelSchemaInferrer.php
├── PathParameters/        # F2: Route constraints → typed Parameters
│   └── PathParameterAnalyzer.php
├── Providers/             # Service providers
├── ResponseSchema/        # F4: JsonResource → response schemas
│   ├── JsonResourceAnalyzer.php
│   ├── ResourceField.php
│   ├── ResponseDetector.php
│   └── ResponseSchemaBuilder.php
├── RouteDiscovery/        # F1: Pattern-based route discovery
│   ├── AutoRouteCollector.php
│   └── PatternMatcher.php
├── RuleParsers/           # F3: Complex validation rule parsers
│   ├── ExampleOverride.php
│   ├── PasswordParser.php
│   └── RequiredWithoutParser.php
├── Support/               # Shared utilities
├── Laragen.php            # Main orchestrator (generate + enrichSpec)
└── OpenAPIGenerator.php   # Extended generator
```

## Key Concepts

### Two-Phase Generation

`Laragen::generate()` follows a two-phase approach:
1. **`buildBaseSpec()`**: Builds base OpenAPI spec using route discovery mode (attribute/auto/combined)
2. **`enrichSpec()`**: Post-processes to add request bodies, security, path parameters, response schemas

Each enrichment is controlled by `autogen.*` config flags and skips operations that already have the data.

### Validation Rule Parsing

Automatically converts Laravel validation rules into JSON Schema for request body definitions.

```
Controller → FormRequest → rules() → RuleParsers → JSON Schema → OpenAPI RequestBody
```

### Zero-Config Philosophy

Unlike the base LaravelOpenApi package (which uses PHP attributes and factories), Laragen generates documentation automatically by analyzing:
- Route definitions and constraints (F1, F2)
- Auth middleware (F6)
- FormRequest validation rules (F3)
- Eloquent model `$casts` (F5)
- JsonResource `toArray()` AST (F4)

## Namespace

`MohammadAlavi\Laragen`

## Configuration

- `config/laragen.php` — Main settings
- `config/rules-to-schema.php` — Rule-to-schema mapping
