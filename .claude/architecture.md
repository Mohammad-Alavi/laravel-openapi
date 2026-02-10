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

### Two-Phase Generation

`Laragen::generate()` follows:
1. **`buildBaseSpec()`** — Builds base OpenAPI spec using route discovery mode (attribute/auto/combined)
2. **`enrichSpec()`** — Post-processes to add request bodies, security, path parameters, response schemas

Each enrichment is controlled by `autogen.*` config flags and skips operations that already have the data.

### Parser Architecture

Parsers implement `RuleParser` (regular) or `ContextAwareRuleParser` (needs access to base schema and all rules). The pipeline runs in two phases:

1. **Regular parsers** — process each field independently (type, format, pattern, constraints)
2. **Context-aware parsers** — can inspect/modify the base schema across fields (conditional logic, cross-field dependencies)

Parser categories:
- **Schema-expressible** — map directly to JSON Schema keywords (pattern, min/max, enum, etc.)
- **Conditional** — use `if/then/else` for cross-field dependencies (required_if, exclude_if, etc.)

### Parser Return Type: ParseResult

All parsers return `ParseResult`, a discriminated union value object with three variants:

- `ParseResult::single($schema)` — the common case; one `LooseFluentDescriptor` for the field
- `ParseResult::expanded([...])` — multiple keyed schemas (only `ConfirmedParser` uses this, producing `field` + `field_confirmed`)
- `ParseResult::excluded()` — field should be omitted (only `ExcludedParser` uses this)

The orchestrator (`RuleToSchema::parseRuleset()`) accumulates schemas across parsers, then returns its own `ParseResult`:
- Empty `$schemas` after all parsers → `ParseResult::excluded()`
- Exactly 1 schema → `ParseResult::single()`
- Multiple schemas → `ParseResult::expanded()`

`NestedObjectParser` receives `parseRuleset` as a callable and uses `$result->isSchema()` / `$result->schema()` to extract child schemas for nested objects and arrays.

### Data Flow: NestedRuleset

`ValidationRuleNormalizer` converts raw Laravel validation rules into `NestedRuleset` trees:

```
Raw rules: ['name' => 'required|string', 'address.street' => 'string', 'tags.*' => 'string']
     ↓ ValidationRuleNormalizer
array<string, NestedRuleset>:
  'name'    => NestedRuleset(validationRules: [required, string], children: [])
  'address' => NestedRuleset(validationRules: [], children: ['street' => NestedRuleset(...)])
  'tags'    => NestedRuleset(validationRules: [], children: ['*' => NestedRuleset(...)])
```

`NestedRuleset` is a `final readonly` value object with:
- `validationRules` — `list<ValidationRule>` for this field
- `children` — `array<string, NestedRuleset>` for nested fields
- `hasChildren()`, `hasWildcardChild()`, `wildcardChild()` — convenience methods

Only 2 of 26 parsers access `$nestedRuleset` body content:
- **`NestedObjectParser`** — uses `->children` and `->hasWildcardChild()` to build nested object/array schemas
- **`TypeParser`** — uses `->hasChildren()` to skip setting `type: array` when a nested schema handles it

Context-aware parsers (`RequiredWithParser`, `RequiredWithoutParser`) access `->validationRules` on entries in `$allRules` (`array<string, NestedRuleset>`).

### Rule Type Resolution

`LaravelRuleType` maps Laravel validation rule names to JSON Schema types. Static methods (`string()`, `integer()`, `number()`, `boolean()`, `array()`, `nullable()`) return rule name lists, and `resolve(string): Type|null` maps a rule name to its JSON Schema `Type` object. `TypeParser` uses `resolve()` for type detection; the `array` type has special handling (skipped when `$nestedRuleset->hasChildren()`). `ExcludedParser` uses `exclude()` separately for field exclusion logic.

### Custom Rule Schema Mapping

`CustomRuleSchemaMapping` is a typed discriminated union for custom rule → JSON Schema mappings configured in `config/rules-to-schema.php`:

- `CustomRuleSchemaMapping::schemaProvider($class)` — delegates to a `HasJsonSchema` implementation resolved via the container
- `CustomRuleSchemaMapping::type('string')` — sets a single JSON Schema type
- `CustomRuleSchemaMapping::types(['null', 'string'])` — sets multiple JSON Schema types
- `CustomRuleSchemaMapping::from($raw)` — factory that converts raw config values (class-string, type string, or array) into the appropriate variant

`RulesToSchemaServiceProvider` converts raw config entries at binding time. `CustomRuleSchemaParser` accepts `array<string, CustomRuleSchemaMapping>` and delegates to `$mapping->apply()`. Inline `HasJsonSchema` rule objects (passed directly in validation rules) take priority over config mappings.

Configuration: `config/laragen.php` and `config/rules-to-schema.php`

See `.claude/architecture/` for future plans: fork strategy, multi-framework support.
