# Architecture

## Package Structure

```
JSONSchema/    MohammadAlavi\ObjectOrientedJSONSchema
oooapi/        MohammadAlavi\ObjectOrientedOpenAPI
src/           MohammadAlavi\LaravelOpenApi
laragen/       MohammadAlavi\Laragen
```

Dependency chain: `JSONSchema -> oooapi -> src (LaravelOpenApi) -> laragen`

Each package can only depend on packages to its left.

## JSONSchema Package

PHP implementation of JSON Schema Draft 2020-12. No framework dependencies.

Key concepts:
- `Schema` static factory for creating typed schemas (`Schema::string()`, `Schema::object()`, etc.)
- `Property` for defining object properties
- `BooleanSchema` (true/false schemas)
- Keyword system for extensibility

## oooapi Package

Object-oriented OpenAPI 3.2 implementation. Framework-agnostic fluent API.

### Core Class Hierarchy

```
Generatable (abstract)
  -> ExtensibleObject (abstract, adds x- extension support)
    -> OpenAPI, Info, Server, PathItem, Operation, Parameter, Header,
       Response, RequestBody, MediaType, Schema, Components, etc.
```

All objects are **immutable** — methods return cloned instances via fluent API.

### Key Patterns

- **`create()` static factories** — Every object has a `static create()` method
- **`toArray()` serialization** — Each object defines `toArray()` returning its fields
- **`compile()`** — Public method on `Generatable` that recursively serializes the tree
- **`Arr::filter()`** — Strips null values from output arrays
- **`MergeableFields`** — Interface for objects whose fields spread into the parent (e.g., SerializationRule, Style)

### Serialization Rules (Parameter/Header)

OAS 3.2 section 4.21.1 groups parameter/header fields into:

1. **Common fields** (on Parameter/Header): `description`, `required`, `deprecated`, `allowEmptyValue`, `example`, `examples`
2. **Schema serialization** (via `SchemaSerialized`): `style`, `explode`, `allowReserved`, `schema`
3. **Content serialization** (via `Content`): `content`

Location: `oooapi/Support/Serialization/`

Concrete types constrain which styles are valid per location:
- `HeaderParameter` — `Simple` only
- `QueryParameter` — `Form`, `DeepObject`, `PipeDelimited`, `SpaceDelimited`
- `PathParameter` — `Simple`, `Label`, `Matrix`
- `CookieParameter` — `Form`, `Cookie`

## LaravelOpenApi Package (src/)

Laravel integration layer. Provides the "Laravel way" of generating OpenAPI.

Key components:
- **Generator** (`src/Generator.php`) — Main orchestrator
- **Builders** (`src/Builders/`) — Factory pattern for building spec components from Laravel routes
- **Attributes** — PHP 8 attributes for annotating controllers/methods
- **Factories** — User-defined factories for schemas, parameters, responses, etc.
- **OpenApiServiceProvider** — Laravel service provider

## Laragen Package

SAAS product for 1-click OpenAPI generation with minimal configuration.

Key components:
- **RuleParsers** (`laragen/RuleParsers/`) — Convert Laravel validation rules to JSON Schema
- Configuration via `config/laragen.php` and `config/rules-to-schema.php`
