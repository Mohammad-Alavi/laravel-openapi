# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

**Read `.claude/` files for deeper context** — architecture details, technical decisions, planned features, coding standards, and Laragen-specific knowledge.

## Project Overview

This repository contains **4 separate packages** developed together for convenience (will be split into separate composer packages later). The goal is to auto-generate OpenAPI definitions for Laravel projects with minimal configuration.

**CRITICAL: Treat each package as independent. Never create direct dependencies between packages - design as if they are separate composer packages.**

## Package Dependency Chain

```
JSONSchema → oooapi → src (LaravelOpenApi) → laragen
```

| Package | Directory | Purpose |
|---------|-----------|---------|
| **JSONSchema** | `JSONSchema/` | PHP implementation of JSON Schema (Draft 2020-12) |
| **oooapi** | `oooapi/` | Object-oriented OpenAPI implementation - fluent API hiding OpenAPI spec complexity |
| **LaravelOpenApi** | `src/` | Laravel integration layer for oooapi - the "Laravel way" of generating OpenAPI |
| **Laragen** | `laragen/` | SAAS product - 1-click OpenAPI generation from Laravel repositories with minimal config |

Each package can only depend on packages to its left in the chain.

## Common Commands

```bash
# Testing (root packages)
composer test                    # Run full test suite
./vendor/bin/pest --parallel     # Run tests in parallel
./vendor/bin/pest --filter="test name"  # Run specific test

# Code Quality (root packages)
composer lint                    # PHPStan static analysis
composer fixer                   # PHP CS Fixer formatting
composer cs                      # PHP CodeSniffer fixes
composer rector                  # Rector code transformations
./vendor/bin/psalm               # Psalm type checking

# Development (root packages)
composer serve                   # Build and serve workbench app
composer build                   # Build testbench workbench
```

## Architecture

### Namespaces

| Package | Namespace |
|---------|-----------|
| JSONSchema | `MohammadAlavi\ObjectOrientedJSONSchema` |
| oooapi | `MohammadAlavi\ObjectOrientedOpenAPI` |
| LaravelOpenApi | `MohammadAlavi\LaravelOpenApi` |
| Laragen | `MohammadAlavi\Laragen` |

### Key Components

- **Generator** (`src/Generator.php`): Main orchestrator for OpenAPI spec generation from Laravel routes
- **Builders** (`src/Builders/`): Factory pattern for building OpenAPI components (schemas, responses, parameters, paths)
- **RuleParsers** (`laragen/RequestSchema/Parsers/`): Convert Laravel validation rules to JSON Schema
- **OpenApiServiceProvider**: Laravel service provider for package registration

### Configuration Files

- `config/openapi.php`: Main OpenAPI settings (collections, component directories)
- `config/laragen.php`: Laragen-specific settings
- `config/rules-to-schema.php`: Validation rule parsing configuration

## Coding Standards

- **PHP 8.2** with strict types everywhere
- **Final classes by default** - prevent unintended inheritance
- **Composition over inheritance**
- **Webmozart\Assert** for assertions (not runtime validation)
- **Value Objects** for complex data structures (avoid arrays/stdClass)
- **No `Interface`/`Trait` suffixes** - use descriptive names (e.g., `Logger` not `LoggerInterface`)
- **Minimal PHPDoc** - only when code isn't self-explanatory. Do not add robotic or unnecessary PHPDoc blocks

### Naming Conventions

| Type | Convention | Example |
|------|------------|---------|
| Classes | PascalCase, noun | `PathParameter`, `Generator` |
| Interfaces | PascalCase, **no suffix** | `Generatable` not `GeneratableInterface` |
| Traits | PascalCase, **no suffix** | Use descriptive name |
| Methods | camelCase, verb | `analyzeRoute()`, `compile()` |
| Properties | camelCase, noun | `$serializationRule` |
| Constants | SCREAMING_SNAKE_CASE | `MAX_DEPTH` |
| Config keys | snake_case | `exclude_patterns` |

## Testing

- Framework: **Pest 3**
- Tests in `tests/` mirror source structure
- Use `describe()` with `covers()` to specify class under test
- Use `class_basename()` for outer describe block names
- All test methods must have `void` return type
- **Avoid mocks** — use real implementations wherever possible and practical. Only mock when absolutely necessary
- **Chain multiple expectations** using `->and()` instead of separate `expect()` calls

```php
describe(class_basename(MyClass::class), function (): void {
    // ...
})->covers(MyClass::class);
```

### Chaining Expectations

Use `->and()` to chain related assertions together:

```php
// ❌ Avoid separate expect() calls
expect($schema->value())->toBeTrue();
expect($schema->isTrue())->toBeTrue();
expect($schema->isFalse())->toBeFalse();

// ✅ Chain with ->and()
expect($schema->value())->toBeTrue()
    ->and($schema->isTrue())->toBeTrue()
    ->and($schema->isFalse())->toBeFalse();
```

## Git Commits

- **Never add Co-Authored-By or any co-author line** to commit messages
- The repository owner is the sole author of all commits
- **Atomic and meaningful commits** — each commit should represent a single logical change with a clear title and description, to keep history readable and reviewable

## Workflow

**CRITICAL: Test-Driven Development (TDD) is mandatory.**

- **Never add or update code without test coverage**
- **Write tests BEFORE implementing new features** (TDD)
- **Add missing tests BEFORE refactoring any existing logic**

1. Write tests first (TDD) for new features
2. Write code following PSR-4 in appropriate namespace directory
3. Run `composer test` until all tests pass
4. Run `composer fixer` for code style
5. Run `composer lint` for static analysis
