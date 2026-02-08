# Coding Standards

## PHP Standards

- **PHP 8.2+** with `declare(strict_types=1)` everywhere
- **PSR-12** coding standard (enforced via PHP CS Fixer)
- **Final classes by default** — prevent unintended inheritance
- **Composition over inheritance**
- **Constructor property promotion** where appropriate
- **Return types on all methods**
- **PHPStan** for static analysis

## Naming Conventions

| Type | Convention | Example |
|------|------------|---------|
| Classes | PascalCase, noun | `PathParameter`, `Generator` |
| Interfaces | PascalCase, **no suffix** | `Generatable` not `GeneratableInterface` |
| Traits | PascalCase, **no suffix** | Use descriptive name |
| Methods | camelCase, verb | `analyzeRoute()`, `compile()` |
| Properties | camelCase, noun | `$serializationRule` |
| Constants | SCREAMING_SNAKE_CASE | `MAX_DEPTH` |
| Config keys | snake_case | `exclude_patterns` |

**No `Interface`/`Trait` suffixes** — use descriptive names (e.g., `Generatable` not `GeneratableInterface`).

## Class Structure

```php
<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ExtensibleObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Arr;

final class Header extends ExtensibleObject
{
    private Description|null $description = null;
    private true|null $required = null;

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
            'required' => $this->required,
            ...$this->mergeFields($this->serializationRule),
        ]);
    }
}
```

Key patterns:
- Private constructor, `create()` static factory
- Immutable: `clone $this` on every setter, return the clone
- `Arr::filter()` strips null values from output
- `true|null` instead of `bool` for optional boolean-only-when-true fields

## Assertions

Use **Webmozart\Assert** for precondition checks and invariants. Not for runtime validation of user input.

```php
use Webmozart\Assert\Assert;

Assert::null($this->examples, 'example and examples fields are mutually exclusive.');
```

## Value Objects

Use value objects for complex data rather than raw arrays or stdClass:

```php
// Good
private readonly Name $name;
private readonly In $in;

// Avoid
private readonly string $name;
private readonly string $in;
```

## PHPDoc

Minimal — only when code isn't self-explanatory:
- Document `@param` and `@return` only when types alone aren't sufficient
- Add `@see` links to relevant OAS spec sections
- Skip obvious getter/setter docs

## Testing Standards

Framework: **Pest 3**

### Test Structure

```php
describe(class_basename(Parameter::class), function (): void {
    describe('query parameter', function (): void {
        it('can be created with minimal setup', function (): void {
            $parameter = Parameter::query(
                'status',
                QueryParameter::create(Schema::string()),
            );

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

### Rules

- Use `describe()` with `covers()` to specify class under test
- Use `class_basename()` for outer describe block name
- All test closures must have `void` return type
- Chain related expectations with `->and()`:

```php
// Chain with ->and()
expect($result)->toHaveKey('name', 'status')
    ->and($result)->toHaveKey('in', 'query');
```

### TDD Workflow

1. Write/update tests first
2. Implement code
3. `composer test` — all tests pass
4. `composer fixer` — code style
5. `composer lint` — static analysis
