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
├── Builders/              # Enhanced builders for auto-detection
├── Console/               # Artisan commands
├── ExampleGenerator/      # Generate example values from schemas
├── Laragen.php            # Main entry point
├── OpenAPIGenerator.php   # Extended generator
├── Providers/             # Service providers
├── RuleParsers/           # Laravel validation → JSON Schema
│   ├── ExampleOverride.php
│   ├── PasswordParser.php
│   └── RequiredWithoutParser.php
└── Support/               # Utility classes
```

## Key Concepts

### Validation Rule Parsing

The core differentiator: automatically converting Laravel validation rules into JSON Schema, which feeds into OpenAPI request body definitions.

```
Controller → FormRequest → rules() → RuleParsers → JSON Schema → OpenAPI RequestBody
```

Configuration in `config/rules-to-schema.php` maps Laravel rules to schema types.

### Zero-Config Philosophy

Unlike the base LaravelOpenApi package (which uses PHP attributes and factories), Laragen aims to generate documentation automatically by analyzing:
- Route definitions
- Controller method signatures
- FormRequest validation rules
- Eloquent model properties
- Response types

## Namespace

`MohammadAlavi\Laragen`

## Configuration

- `config/laragen.php` — Main settings
- `config/rules-to-schema.php` — Rule-to-schema mapping
