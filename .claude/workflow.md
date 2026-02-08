# Instructions

## Project Context

This repository contains 4 packages for auto-generating OpenAPI from Laravel code. See `CLAUDE.md` for the primary reference.

## Core Principles

### 1. Package Independence
- Each package is independent and could be a separate repo
- Only depend through the chain: `JSONSchema -> oooapi -> src (LaravelOpenApi) -> laragen`
- Never import between packages outside this chain

### 2. Immutable Objects + Fluent Builders
- All schema and OpenAPI objects are immutable
- Use fluent builder pattern: `create()` factory + clone-on-modify setters
- Never mutate state

### 3. TDD Mandatory
- Write tests BEFORE implementing features
- Add missing tests BEFORE refactoring
- Never merge without test coverage

## Namespaces

| Package | Directory | Namespace |
|---------|-----------|-----------|
| JSONSchema | `JSONSchema/` | `MohammadAlavi\ObjectOrientedJSONSchema` |
| oooapi | `oooapi/` | `MohammadAlavi\ObjectOrientedOpenAPI` |
| LaravelOpenApi | `src/` | `MohammadAlavi\LaravelOpenApi` |
| Laragen | `laragen/` | `MohammadAlavi\Laragen` |

## Common Commands

```bash
composer test        # Run full test suite
composer fixer       # PHP CS Fixer formatting
composer lint        # PHPStan static analysis
composer cs          # PHP CodeSniffer fixes
composer rector      # Rector code transformations
composer build       # Build testbench workbench
composer serve       # Build and serve workbench app
```

## Development Workflow

1. Write/update tests first (TDD)
2. Implement in appropriate namespace directory following PSR-4
3. `composer test` — all tests pass
4. `composer fixer` — code style
5. `composer lint` — static analysis

## Key Patterns

### oooapi Object Pattern

```php
final class Header extends ExtensibleObject
{
    private Description|null $description = null;

    private function __construct(
        private readonly SerializationRule|null $serializationRule = null,
    ) {
    }

    public static function create(Content|HeaderParameter|null $serializationRule = null): self
    {
        return new self($serializationRule);
    }

    public function description(string $description): self
    {
        $clone = clone $this;
        $clone->description = Description::create($description);
        return $clone;
    }

    public function toArray(): array
    {
        return Arr::filter([
            'description' => $this->description,
            ...$this->mergeFields($this->serializationRule),
        ]);
    }
}
```

### LaravelOpenApi Factory Pattern

```php
#[Collection(WorkbenchCollection::class)]
final class Limit extends ParameterFactory implements ShouldBeReferenced
{
    public function component(): Parameter
    {
        return Parameter::query(
            'limit',
            QueryParameter::create(
                Schema::integer()
                    ->minimum(0)
                    ->default(10)
                    ->description('The maximum number of items to return.'),
            ),
        );
    }
}
```

### Test Pattern

```php
describe(class_basename(Parameter::class), function (): void {
    describe('query parameter', function (): void {
        it('can be created with minimal setup', function (): void {
            $parameter = Parameter::query('status', QueryParameter::create(Schema::string()));
            $result = $parameter->compile();
            expect($result)->toBe([
                'name' => 'status',
                'in' => 'query',
                'schema' => ['type' => 'string'],
            ]);
        });
    });
})->covers(Parameter::class);
```

## What NOT to Do

1. **Don't mutate objects** — always return cloned instances
2. **Don't cross package boundaries** — respect the dependency chain
3. **Don't add Interface/Trait suffixes** — use descriptive names
4. **Don't skip tests** — TDD is mandatory
5. **Don't add premature abstractions** — tolerate some duplication
