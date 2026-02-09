# Multi-Framework PHP Architecture

## Goal

Support OpenAPI generation for multiple PHP frameworks (Symfony, Slim, API Platform) beyond just Laravel, enabling Laragen's core analysis engine to work across the PHP ecosystem.

## Laravel-Specific Code in Laragen

Current Laragen code tightly coupled to Laravel:

| Component | Laravel Dependency | Usage |
|-----------|-------------------|-------|
| Route discovery | `Illuminate\Routing\Route`, `Router` | Iterating registered routes |
| FormRequest extraction | `Illuminate\Foundation\Http\FormRequest` | Extracting validation rules |
| Auth middleware detection | `Route::middleware()`, middleware names | Detecting auth schemes |
| Service provider | `Illuminate\Support\ServiceProvider` | Package registration |
| Config system | `config()` helper, `config/*.php` files | Runtime configuration |
| Model schema | `Illuminate\Database\Eloquent\Model` | Inferring schema from casts |
| Validation rules | `Illuminate\Validation\Rules\*` | Rule objects (Password, NotIn, etc.) |
| Container | `app()`, `resolve()` | Dependency injection |

## Abstraction Design

### Core (Framework-Agnostic)

The analysis engine that converts code artifacts into OpenAPI schemas:

```
laragen-core/
  SchemaInference/        -- Type/rule → JSON Schema conversion
    RuleToSchemaEngine    -- Validation rule parsing (framework-agnostic rule format)
    TypeInferrer          -- PHP type → JSON Schema mapping
  AnnotationParser/       -- Docblock tag parsing (@response, @bodyParam, etc.)
  ResponseAnalyzer/       -- AST-based response analysis
  OpenAPIAssembler/       -- Combines analysis results into OpenAPI spec
```

### Adapter Interfaces

Each framework adapter implements these interfaces:

```php
interface RouteCollector
{
    /** @return iterable<RouteInfo> */
    public function collectRoutes(): iterable;
}

interface RequestExtractor
{
    public function extractValidationRules(RouteInfo $route): array;
    public function extractRequestClass(RouteInfo $route): ?string;
}

interface AuthDetector
{
    /** @return SecurityScheme[] */
    public function detectSecuritySchemes(RouteInfo $route): array;
}

interface ModelInspector
{
    public function inspectModel(string $modelClass): SchemaDefinition;
}

interface ConfigProvider
{
    public function get(string $key, mixed $default = null): mixed;
}
```

### Value Objects (Shared)

```php
// Framework-agnostic route representation
final readonly class RouteInfo
{
    public function __construct(
        public string $method,        // GET, POST, etc.
        public string $uri,           // /api/users/{id}
        public string $controller,    // Fully qualified class name
        public string $action,        // Method name
        public array $middleware,      // Middleware names
        public array $parameters,     // URI parameters
    ) {}
}

// Framework-agnostic validation rule
final readonly class ValidationRule
{
    public function __construct(
        public string $name,          // 'required', 'string', 'between', etc.
        public array $parameters,     // ['3', '10'] for between:3,10
    ) {}
}
```

## Package Structure

```
laragen-core/              -- Framework-agnostic analysis engine
  composer.json            -- No framework dependencies
  src/
    Contracts/             -- Adapter interfaces
    SchemaInference/       -- Rule and type → schema conversion
    AnnotationParser/      -- Docblock parsing
    ResponseAnalyzer/      -- AST analysis
    OpenAPIAssembler/      -- Spec assembly

laragen-laravel/           -- Laravel adapter (current Laragen, refactored)
  composer.json            -- Requires laragen-core + illuminate/*
  src/
    Adapters/
      LaravelRouteCollector.php
      FormRequestExtractor.php
      LaravelAuthDetector.php
      EloquentModelInspector.php
      LaravelConfigProvider.php
    Providers/
      LaragenServiceProvider.php
    Support/
      LaravelValidationRuleNormalizer.php

laragen-symfony/           -- Symfony adapter (future)
  composer.json            -- Requires laragen-core + symfony/*
  src/
    Adapters/
      SymfonyRouteCollector.php
      FormTypeExtractor.php
      SymfonyAuthDetector.php
      DoctrineModelInspector.php

laragen-slim/              -- Slim adapter (future)
  ...
```

## Migration Path

### Phase 1: Identify Boundaries

Map every file in `laragen/` to either "core" or "laravel-specific":

- **Core**: DocBlockTagParser, ArraySchemaAnalyzer, parser logic, schema inference
- **Laravel-specific**: RuleToSchema, RuleExtractor, AuthDetector, LaragenServiceProvider, config system

### Phase 2: Extract Core

1. Create `laragen-core` package with zero framework dependencies
2. Move framework-agnostic code with new interfaces
3. Laragen-laravel implements the adapter interfaces wrapping current code
4. Ensure no behavioral changes — this is a structural refactor

### Phase 3: Framework Adapters

1. **Symfony**: Route collection from `RoutingBundle`, validation from `Form` component constraints
2. **Slim**: Route collection from Slim router, validation from external libraries
3. **API Platform**: Already has OpenAPI generation — adapter may focus on enhancement/compatibility

## Key Design Decisions

1. **Validation rules as strings**: The core engine works with normalized string rules (`['required', 'string', 'between:3,10']`), not framework-specific rule objects. Each adapter normalizes its framework's rules to this format.

2. **AST analysis stays in core**: Response analysis via AST (nikic/php-parser) is framework-agnostic — it reads PHP code, not framework APIs.

3. **Config is an interface**: No `config()` helper calls in core. The `ConfigProvider` interface is implemented by each framework adapter.

4. **No container in core**: Core classes use constructor injection only. Framework adapters wire things up through their DI containers.
