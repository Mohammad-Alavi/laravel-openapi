# FluentJsonSchema Replacement Strategy

## Problem

The validation-rule-to-JSON-Schema pipeline currently uses `riley19280/fluent-json-schema` (`FluentSchema`) as an intermediate representation. The flow is:

```
Validation Rules -> RuleParsers -> FluentSchema -> compile() -> array -> Schema::from(array)
```

This creates several issues:

1. **Indirection**: The compile-to-array-to-Schema roundtrip is unnecessary — parsers could produce `Schema` objects directly.
2. **Tight coupling**: Three components access `getSchemaDTO()` directly, coupling to FluentSchema internals:
   - `RuleToSchema` — reads `properties`, `required` from the DTO
   - `RequiredWithParser` — reads/writes `properties`, `required`, calls `allOf()`/`anyOf()`
   - `ExampleOverride` — reads `examples` from the DTO
3. **API mismatch**: Some operations (like `enum()`) aren't available on `FluentSchema` directly and require direct DTO manipulation (`$schema->getSchemaDTO()->enum = $values`).
4. **Maintenance burden**: Any FluentSchema internal change can break our parsers silently.

## Target State

Parsers produce our `Schema` objects (from the JSONSchema package) directly, eliminating FluentSchema entirely.

```
Validation Rules -> RuleParsers -> Schema (JSONSchema package) -> OpenAPI Schema
```

## Migration Path

### Phase 1: Adapter Interface

Create a `SchemaAccumulator` interface that abstracts the operations parsers need:

```php
interface SchemaAccumulator
{
    public function setType(string $type): static;
    public function setPattern(string $pattern): static;
    public function setMinLength(int $min): static;
    public function setMaxLength(int $max): static;
    public function setMinimum(int|float $min): static;
    public function setMaximum(int|float $max): static;
    public function setMinItems(int $min): static;
    public function setMaxItems(int $max): static;
    public function setMultipleOf(int|float $value): static;
    public function setUniqueItems(bool $unique): static;
    public function setFormat(string $format): static;
    public function setEnum(array $values): static;
    public function setConst(mixed $value): static;
    public function setNot(SchemaAccumulator $schema): static;
    public function setRequired(array $fields): static;
    public function setProperties(array $properties): static;
    public function setIf(SchemaAccumulator $if): static;
    public function setThen(SchemaAccumulator $then): static;
    public function setElse(SchemaAccumulator $else): static;
    public function getType(): ?string;
    public function compile(): array;
}
```

Implement two adapters:
- `FluentSchemaAccumulator` — wraps `FluentSchema` (backward-compatible)
- `NativeSchemaAccumulator` — wraps our `Schema` objects (target)

### Phase 2: Migrate Parsers

Update parsers one by one to accept `SchemaAccumulator` instead of `FluentSchema`. Since the interface is narrower than FluentSchema, this naturally limits what parsers can do.

Priority order:
1. Simple parsers first (StringPatternParser, AcceptedDeclinedParser, etc.)
2. Type-aware parsers (ComparisonConstraintParser, AdditionalConstraintParser)
3. Context-aware parsers (RequiredWithParser, RequiredWithoutParser)
4. RuleToSchema orchestrator

### Phase 3: Remove FluentSchema

Once all parsers use `SchemaAccumulator`:
1. Remove `FluentSchemaAccumulator`
2. Remove `riley19280/fluent-json-schema` from composer.json
3. Rename `NativeSchemaAccumulator` or inline it

## Key Challenges

### `getSchemaDTO()` Access

The most invasive coupling. Three components use it:

| Component | DTO Access | Replacement |
|-----------|-----------|-------------|
| `RuleToSchema` | `properties`, `required` | `SchemaAccumulator::getProperties()`, `getRequired()` |
| `RequiredWithParser` | `properties`, `required`, `allOf()`, `anyOf()` | `SchemaAccumulator` composition methods |
| `ExampleOverride` | `examples` | `SchemaAccumulator::getExamples()`, `setExamples()` |

### Vendor Parser Compatibility

The `riley19280/laravel-rules-to-schema` vendor parsers (TypeParser, MiscPropertyParser, etc.) expect `FluentSchema`. Options:
1. **Fork and migrate** — Change vendor parsers to use `SchemaAccumulator`
2. **Absorb** — Rewrite vendor parser logic directly into Laragen parsers
3. **Bridge** — Keep FluentSchema for vendor parsers, convert at boundary

Option 2 (absorb) aligns best with the long-term goal of independence.

## Files Affected

- `laragen/RequestSchema/RuleToSchema.php` — main orchestrator, must change parser dispatch
- `laragen/RequestSchema/Parsers/*` — all 17+ parser files
- `laragen/RequestSchema/ExampleGenerator/*` — example generation pipeline
- `config/rules-to-schema.php` — may need updated parser signatures
- Tests for all above
