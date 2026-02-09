# Laragen: Automatic OpenAPI Generation

Laragen provides zero-config OpenAPI generation for Laravel applications. It analyzes your codebase and generates comprehensive API documentation with minimal setup.

## What Laragen Does

Laragen inspects your Laravel application and auto-generates OpenAPI schemas by analyzing:

- **Route discovery** — finds API routes by URI patterns (no annotations needed)
- **Path parameters** — infers types from route constraints (`whereUuid`, `whereNumber`, etc.)
- **Request body schemas** — from FormRequest validation rules, Spatie Data objects, or docblock annotations
- **Response schemas** — from JsonResource, ResourceCollection, Spatie Data, Fractal Transformer, Eloquent Model, or docblock annotations
- **Authentication** — detects auth middleware and generates SecurityScheme components

## Supported Auto-Detection

### Request Body

| Source | Description |
|--------|-------------|
| `@bodyParam` annotations | Explicit parameter definitions in docblocks |
| `@queryParam` annotations | Explicit query parameter definitions in docblocks |
| Spatie Data objects | Type-hinted `Data` parameters in controller methods |
| FormRequest rules | Laravel validation rules converted to JSON Schema |

### Response Schema

| Source | Description |
|--------|-------------|
| `@response` annotations | Explicit JSON response examples in docblocks |
| ResourceCollection | Detects `ResourceCollection` return types |
| JsonResource | Analyzes `toArray()` method AST |
| Spatie Data | Analyzes Data class constructor parameters |
| Fractal Transformer | Analyzes `transform()` method AST |
| Eloquent Model | Infers schema from `$casts`, `$hidden`, `$appends` |

Detection strategies are tried in the order listed above. The first match wins.

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=laragen-config
```

### `config/laragen.php`

```php
return [
    'enabled' => env('LARAGEN_ENABLED', true),
    'docs_path' => '.laragen/openapi.json',

    'route_discovery' => [
        'mode' => 'attribute', // 'auto' | 'attribute' | 'combined'
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

    'strategies' => [
        'request' => [
            'prepend' => [], // Custom strategies before built-in
            'append' => [],  // Custom strategies after built-in
        ],
        'response' => [
            'prepend' => [],
            'append' => [],
        ],
    ],
];
```

### Route Discovery Modes

| Mode | Behavior |
|------|----------|
| `attribute` | Only routes with `#[Collection]` attribute |
| `auto` | Routes matching `include` patterns (excluding `exclude` patterns) |
| `combined` | Both attributed and pattern-matched routes |

## Usage

Generate the OpenAPI spec:

```bash
php artisan laragen:generate
```

The generated spec is available at the configured `docs_path` and served at `/laragen/docs`.
