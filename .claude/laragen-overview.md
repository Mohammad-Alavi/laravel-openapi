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
├── Annotations/              # F8: Docblock annotation parsing
│   ├── DocBlockTagParser.php
│   ├── DetectedResponseAnnotation.php
│   ├── DetectedBodyParam.php
│   └── DetectedQueryParam.php
├── Auth/                     # F6: Auth middleware → SecurityScheme
│   ├── AuthDetector.php
│   ├── AuthScheme.php
│   ├── BasicSecuritySchemeFactory.php
│   ├── BearerSecuritySchemeFactory.php
│   └── SecuritySchemeRegistry.php
├── Console/                  # Artisan commands
│   └── Generate.php
├── ModelSchema/              # F5: Eloquent model → JSON Schema
│   ├── CastAnalyzer.php
│   └── ModelSchemaInferrer.php
├── PathParameters/           # F2: Route constraints → typed Parameters
│   └── PathParameterAnalyzer.php
├── Providers/                # Service providers
│   └── LaragenServiceProvider.php
├── RequestSchema/            # F7: Request schema strategies
│   ├── Annotation/           # Annotation strategies (@bodyParam, @queryParam)
│   │   ├── AnnotationBodyParamDetector.php
│   │   ├── AnnotationBodyParamSchemaBuilder.php
│   │   ├── AnnotationQueryParamDetector.php
│   │   └── AnnotationQueryParamSchemaBuilder.php
│   ├── ExampleGenerator/     # Generate example values from schemas
│   ├── Parsers/              # Custom validation rule parsers
│   │   ├── ContextAwareRuleParser.php
│   │   ├── CustomRuleDocsParser.php
│   │   ├── ExampleOverride.php
│   │   ├── FileUploadParser.php
│   │   ├── PasswordParser.php
│   │   ├── RequiredWithParser.php
│   │   └── RequiredWithoutParser.php
│   ├── SpatieData/           # Spatie Data request strategy
│   └── ValidationRules/      # FormRequest validation rules strategy
├── ResponseSchema/           # F4: Response schema strategies
│   ├── Annotation/           # Annotation strategy (@response)
│   │   ├── AnnotationResponseDetector.php
│   │   └── AnnotationResponseSchemaBuilder.php
│   ├── ArraySchema/          # Generic array-return AST analysis
│   │   ├── ArrayField.php
│   │   └── ArraySchemaAnalyzer.php
│   ├── EloquentModel/        # Eloquent Model strategy
│   ├── FractalTransformer/   # Fractal strategy (conditional)
│   ├── JsonResource/         # JsonResource strategy
│   ├── ResourceCollection/   # ResourceCollection strategy
│   └── SpatieData/           # Spatie Data response strategy
├── RouteDiscovery/           # F1: Pattern-based route discovery
│   ├── AutoRouteCollector.php
│   └── PatternMatcher.php
├── Scribe/                   # Scribe integration
│   └── OpenAPIGenerator.php
├── Support/                  # Shared utilities
│   └── Config/
└── Laragen.php               # Main orchestrator
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

### Docblock Annotations (Override)

When automatic detection isn't sufficient, Scribe-compatible docblock annotations provide explicit control:

```php
/**
 * @response 200 {"id": 1, "name": "John"}
 * @bodyParam name string required The user's name
 * @queryParam page integer The page number
 */
```

Annotation strategies run first in the chain, so they always override automatic detection.

### Zero-Config Philosophy

Unlike the base LaravelOpenApi package (which uses PHP attributes and factories), Laragen generates documentation automatically by analyzing:
- Docblock annotations — `@response`, `@bodyParam`, `@queryParam` (F8)
- Route definitions and constraints (F1, F2)
- Auth middleware (F6)
- FormRequest validation rules (F3, F7)
- Spatie Data objects (F7)
- Eloquent model `$casts` (F5)
- JsonResource/ResourceCollection `toArray()` AST (F4)
- Fractal Transformer `transform()` AST (F4)

## Namespace

`MohammadAlavi\Laragen`

## Configuration

- `config/laragen.php` — Main settings
- `config/rules-to-schema.php` — Rule-to-schema mapping
