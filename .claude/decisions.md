# Technical Decisions

Key decisions made in this project and their rationale.

---

## D1: OpenAPI 3.2 (Latest Spec)

**Decision**: Target OpenAPI 3.2, not older versions.

**Rationale**:
- 3.2 is fully JSON Schema compatible (Draft 2020-12)
- Adds querystring parameter location, encoding improvements
- Simplifies codebase -- one spec version
- 3.0.x had awkward JSON Schema subset differences

**Mitigation**: Most modern tools support 3.1+; provide export to 3.0.x if demand exists.

---

## D2: JSON Schema Draft 2020-12

**Decision**: Implement JSON Schema Draft 2020-12 as its own standalone package.

**Rationale**:
- Native in OpenAPI 3.1+ / 3.2
- Latest stable draft with best tooling support
- `$ref` works alongside other keywords
- Keyword system enables extensibility

---

## D3: Immutable Objects with Clone-on-Modify

**Decision**: All schema/OpenAPI objects are immutable. Methods return cloned instances.

```php
$a = Schema::string();
$b = $a->format('email');  // $a is unchanged
```

**Rationale**: Prevents bugs from shared references, easier to reason about, works naturally with fluent chaining.

---

## D4: Package Independence

**Decision**: Four packages developed in one repo but designed as if separate composer packages.

**Chain**: `JSONSchema -> oooapi -> src (LaravelOpenApi) -> laragen`

**Rationale**: Clear boundaries, testable in isolation. Will eventually be split into separate repos.

---

## D5: Type-System Enforcement Over Runtime Checks

**Decision**: Use PHP union types and class hierarchy to enforce constraints at the type level where possible.

```php
public static function query(string $name, Content|QueryParameter $serialization): self
public static function header(string $name, Content|HeaderParameter $serialization): self
```

**Rationale**: Catches errors at write-time (IDE support), self-documenting API, no invalid state possible.

---

## D6: Serialization Rules as Mergeable Fields

**Decision**: Parameter and Header serialization is modeled as `SerializationRule` objects that implement `MergeableFields`. Their fields spread into the parent's `toArray()`.

**Rationale**: OAS 3.2 section 4.21.1 defines three field groups. `MergeableFields` + `...$this->mergeFields()` keeps the output flat.

---

## D7: Pest PHP for Testing

**Decision**: Use Pest PHP (v3) over raw PHPUnit.

**Rationale**: More expressive syntax with `describe()`/`it()`, `covers()` for coverage tracking, `->and()` chaining, first-class Laravel support.

---

## D8: Webmozart Assert for Preconditions

**Decision**: Use `webmozart/assert` for assertion-based precondition checks, not for user input validation.

**Rationale**: Cleaner than manual if/throw blocks. Used for developer-facing API constraints.

---

## D9: No Premature Abstractions

**Decision**: Tolerate some code duplication rather than extracting shared abstractions too early. Only extract when there are 4+ identical usages with no divergence risk.

---

## D10: MIT License for Package, Proprietary for Platform

**Decision**: Open-source the analysis engine (MIT), keep SaaS platform proprietary.

**Rationale**: Community trust, contributions improve core. Hosted features (webhooks, containers, collaboration) are hard to replicate.

---

## D11: Graceful Degradation Over Strict Errors

**Decision**: When analysis fails for a route/component, skip it with a warning rather than failing entire generation.

**Rationale**: 90% documentation is better than 0% due to one error. Provide a `--strict` flag for those who want failures.

---

## D12: Internal Schema Objects Before JSON Output

**Decision**: Build internal PHP objects representing OpenAPI structures, then serialize to JSON.

**Rationale**: Type safety, self-validating objects, easier manipulation, JsonSerializable for clean output.

---

## D13: Schema Registry for $ref Management

**Decision**: Use a central registry (Components) to manage component references. `ShouldBeReferenced` interface marks factories that should become components.

**Rationale**: DRY specs, smaller output, handles circular refs.

---

## D14: Leverage Open-Source, Maintain Clear Boundaries

**Decision**: Depend on open-source tools (Scribe, laravel-rules-to-schema, nikic/php-parser) but wrap external dependencies behind our own interfaces.

**Boundaries**: No direct Scribe/external types in our public API. Internal code depends on our interfaces.

---

## D22: Prefer Laravel Built-in Features Over Custom Implementations

**Decision**: Always try to use Laravel built-in features first. Only implement custom solutions when Laravel doesn't provide the functionality.

**Application**: Before implementing any detection logic, check if Laravel's Router, Reflection, Service Container, or other built-in APIs already provide the needed information.

---

## Laragen-Specific Decisions

### LD1: Extend, Don't Replace LaravelOpenApi

**Decision**: Laragen extends the LaravelOpenApi generators and builders rather than replacing them. Users who want manual control use LaravelOpenApi directly; users who want zero-config use Laragen.

### LD2: Configurable Rule-to-Schema Mapping

**Decision**: Validation rule -> JSON Schema mapping is configurable via `config/rules-to-schema.php`. Custom rules need custom mappings without code changes.

### LD3: Custom RuleParsers for Complex Rules

**Decision**: Complex validation rules get dedicated parser classes rather than simple config mappings. Two categories:

- **Schema-expressible** (`RuleParser`): Map directly to JSON Schema keywords (pattern, min/max, enum, etc.)
- **Context-aware** (`ContextAwareRuleParser`): Need access to base schema and all rules for cross-field `if/then/else` logic

Both `RequiredWithParser` and `RequiredWithoutParser` use the `if/then` approach on the base schema's `allOf`, preserving root-level properties. `RequiredWithParser` uses `if: { required: [arg] }` (single arg) or `if: { anyOf: [...] }` (multiple args) without a `not` wrapper, while `RequiredWithoutParser` wraps the if-condition in `not`.

### LD4: Example Generation from Schema

**Decision**: Auto-generate example values from JSON Schema definitions. `ExampleOverride` allows manual overrides when auto-generation isn't sufficient.

### LD5: Strategy Chain for Response Detection

**Decision**: Pluggable `ResponseStrategy` chain (detector + builder pairs) tried in order. Enables adding new strategies (Spatie Data, raw arrays) without modifying existing code. Current order: JsonResource -> FractalTransformer (conditional) -> EloquentModel.

---

## Future Platform Decisions

### D15: Stoplight Elements for Documentation UI

Use Stoplight Elements (React) instead of Swagger UI. Modern design, MIT licensed, "Try It Out" built-in.

### D16: Containerized Analysis for Security

Run user code analysis in isolated Docker containers. Read-only filesystem, no network, 512MB memory limit, 5-minute timeout.

### D17: PostgreSQL Over MySQL

Native JSONB support for storing OpenAPI specs, better performance for complex queries.

### D18: Webhook-First Architecture

`GitHub Push -> Webhook -> Queue Job -> Container -> Build -> Update Docs`

### D19: Subdomain-Based Project Routing

Each project gets `{project-slug}.laragen.com`. Cleaner URLs, easier custom domain support.

### D20: Inertia.js Over Livewire

Inertia.js with React for the dashboard. Consistent with Stoplight Elements (React), better TypeScript support.

### D21: Stripe via Laravel Cashier

Stripe for payments. Solo: $19/mo (3 projects), Team: $49/mo (10), Agency: $99/mo (25), Enterprise: $249/mo (unlimited).

---

## D24: No Redundant Intermediate Variables

**Decision**: Inline single-use variables that add no clarity. If a variable is assigned and immediately used/returned with nothing else in between, inline the expression directly.

**Examples**:
```php
// Bad: $x assigned then immediately returned
$transformer = app(RuleToSchema::class);
return $transformer->transform($ruleSets, $request);

// Good: inline
return app(RuleToSchema::class)->transform($ruleSets, $request);
```

**Exceptions**: Keep intermediate variables when they:
- Are used more than once (avoids duplicate computation)
- Name a complex expression for readability (e.g., `$ifSchema`, `$thenSchema` in conditional logic)
- Would create excessively long lines if inlined

**Applied to**: `laravel-rules-to-schema/` and `laragen/RequestSchema/` — completed.

**Remaining work**: Audit `src/`, `oooapi/`, `JSONSchema/`, and remaining `laragen/` directories.

---

## D23: Strict Keyword Objects Over Loose Strings

**Decision**: Always use typed keyword objects (`Type::string()`, `StringFormat::EMAIL`) instead of loose string literals (`'string'`, `'email'`) when calling JSONSchema fluent descriptor methods.

**Rationale**:
- Type-safety: IDE autocomplete, compile-time errors vs runtime failures
- Self-documenting: `Type::object()` is clearer intent than `'object'`
- Consistent with D5 (Type-System Enforcement Over Runtime Checks)

**Applied to**: `laravel-rules-to-schema/` package (source + tests) — completed.

**Remaining work**:
- `tests/JSONSchema/` — ~90 instances of `->type('...')` and `->format('...')` with string literals. These are in the JSONSchema package's own tests and should use `Type::*()` and `StringFormat::*` where applicable.
- Custom/dynamic values from external config or user-defined rules (`CustomRuleDocsParser`, `CustomRuleSchemaParser`) stay as strings since the values aren't known at compile time.
- Non-standard format strings (`'binary'`, `'timezone'`) stay as strings since they have no `StringFormat` enum case.

---

## D25: NestedRuleset Value Object Over Magic-Key Arrays

**Decision**: Replace unstructured arrays with magic key (`##_VALIDATION_RULES_##`) with a typed `NestedRuleset` value object for the normalized validation ruleset flowing through parsers.

**Before**: `['##_VALIDATION_RULES_##' => [ValidationRule, ...], 'child' => [...]]` — parsers filtered by magic key, used `array_diff_key` and `array_filter` to separate rules from children.

**After**: `NestedRuleset(validationRules: [...], children: ['child' => NestedRuleset(...)])` — typed access via properties and methods.

**Rationale**:
- Self-documenting data structure — no magic string constants in parser code
- Type safety — `NestedRuleset` in signatures instead of `array`
- Only 2 of 26 parsers actually use the nested data; the value object makes this explicit
- `RULES_KEY` constant retained on `ValidationRuleNormalizer` for potential external consumers

**Scope**: `laravel-rules-to-schema/` package only. The `laragen/` `Example` class has its own `$nestedRuleset` array property in a separate domain — not affected.

---

## D26: CustomRuleSchemaMapping Value Object Over Untyped Config Arrays

**Decision**: Replace `array<string, mixed>` config entries for custom rule schemas with a typed `CustomRuleSchemaMapping` value object.

**Before**: `CustomRuleSchemaParser` accepted raw config values (`class-string`, `'string'`, `['null', 'string']`) and used inline `is_string`/`is_array`/`class_exists`/`instanceof` checks to discriminate between the 3 shapes.

**After**: `CustomRuleSchemaMapping` is a discriminated union with named constructors (`schemaProvider()`, `type()`, `types()`), a `from()` factory for raw config conversion, and an `apply()` method encapsulating schema generation. `CustomRuleSchemaParser` accepts `array<string, CustomRuleSchemaMapping>` and delegates to `$mapping->apply()`.

**Rationale**:
- Type discrimination logic extracted from parser into self-contained value object
- `from()` factory handles backward-compatible conversion of raw config values
- `apply()` method encapsulates schema generation — parser only routes to the right mapping
- Service provider converts raw config at binding time via `CustomRuleSchemaMapping::from()`

**Scope**: `laravel-rules-to-schema/` package only. Config file format unchanged — `from()` handles conversion.

---

## Open Questions

- **Livewire/Inertia responses**: Skip for MVP, not traditional JSON APIs.
- **API versioning**: Support path-based (`/v1/`) in MVP, headers later.
- **"Try It Out" auth**: Client-side only token storage with clear warnings.
- **Free tier limits**: 3 projects, 50 builds/month.
