# Technical Decisions

Key decisions made in this project and their rationale.

---

## D1: OpenAPI 3.2 (Latest Spec)

**Decision**: Target OpenAPI 3.2, not older versions.

**Rationale**:
- 3.2 is fully JSON Schema compatible (Draft 2020-12)
- Adds querystring parameter location, encoding improvements
- Simplifies codebase — one spec version
- 3.0.x had awkward JSON Schema subset differences

**Trade-offs**:
- (+) Cleaner codebase, one spec version
- (+) Better JSON Schema tooling compatibility
- (-) Some legacy tools only support older versions
- (-) May need to add conversion layer later

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

**Rationale**:
```php
$a = Schema::string();
$b = $a->format('email');  // $a is unchanged
```
- Prevents bugs from shared references
- Easier to reason about
- Works naturally with fluent chaining

**Trade-off**: Slightly more memory (cloning), but negligible for spec generation.

---

## D4: Package Independence

**Decision**: Four packages developed in one repo but designed as if separate composer packages.

**Chain**: `JSONSchema -> oooapi -> src (LaravelOpenApi) -> laragen`

**Rationale**:
- Clear boundaries and testable in isolation
- json-schema and oooapi are framework-agnostic
- Will eventually be split into separate repos
- Prevents accidental tight coupling

---

## D5: Type-System Enforcement Over Runtime Checks

**Decision**: Use PHP union types and class hierarchy to enforce constraints at the type level where possible, rather than runtime assertions.

**Example** — Parameter serialization:
```php
// Type system enforces valid styles per location
public static function query(string $name, Content|QueryParameter $serialization): self
public static function header(string $name, Content|HeaderParameter $serialization): self
```
Instead of accepting any `SerializationRule` and throwing at runtime if the style doesn't match.

**Rationale**:
- Catches errors at write-time (IDE support) rather than runtime
- Self-documenting API
- No invalid state possible

---

## D6: Serialization Rules as Mergeable Fields

**Decision**: Parameter and Header serialization (schema-based or content-based) is modeled as `SerializationRule` objects that implement `MergeableFields`. Their fields spread into the parent's `toArray()`.

**Rationale**:
- OAS 3.2 section 4.21.1 defines three field groups for parameters/headers
- Common fields live on Parameter/Header directly
- Serialization fields are composed via `SerializationRule`
- `MergeableFields` + `...$this->mergeFields()` keeps the output flat (no nesting)

---

## D7: Pest PHP for Testing

**Decision**: Use Pest PHP (v3) over raw PHPUnit.

**Rationale**:
- More expressive syntax with `describe()`/`it()`
- `covers()` for explicit class coverage tracking
- `->and()` chaining for related assertions
- First-class Laravel support
- Modern standard for Laravel packages

---

## D8: Webmozart Assert for Preconditions

**Decision**: Use `webmozart/assert` for assertion-based precondition checks, not for user input validation.

**Rationale**:
- Throws `InvalidArgumentException` on violations
- Cleaner than manual if/throw blocks
- Used for developer-facing API constraints (e.g., mutual exclusivity of `example`/`examples`)

---

## D9: No Premature Abstractions

**Decision**: Tolerate some code duplication rather than extracting shared abstractions too early.

**Example**: `description()`, `example()`, `examples()` methods are duplicated across `Parameter`, `Header`, `MediaType` rather than extracted into a trait or base class.

**Rationale**:
- Only extract when there are 4+ identical usages with no divergence risk
- `self` return type prevents shared trait usage cleanly
- Each class may diverge in validation rules or error messages
- Three occurrences is not enough to justify abstraction

---

## D10: MIT License for Package, Proprietary for Platform

**Decision**: Open-source the analysis engine (MIT), keep SaaS platform proprietary.

**Rationale**:
- MIT builds community trust (no bait-and-switch fears)
- Community contributions improve the core engine
- Hosted features (webhooks, containers, team collaboration) are hard to replicate
- GitLab model has proven successful

**Trade-offs**:
- (+) Community adoption and contributions
- (+) Marketing through open source
- (-) Someone could build competing SaaS
- (-) Support burden for free users

**Mitigation**: Our SaaS value is in the infrastructure and convenience, not the code.

---

## D11: Graceful Degradation Over Strict Errors

**Decision**: When analysis fails for a route/component, skip it with a warning rather than failing the entire generation.

**Rationale**:
- Real-world projects have edge cases we can't anticipate
- 90% documentation is better than 0% due to one error
- Users can incrementally fix issues
- Matches user expectations from similar tools

**Trade-offs**:
- (+) More forgiving, works on more projects
- (+) Better user experience
- (-) May hide real problems
- (-) Incomplete documentation without clear indication

**Mitigation**: Always log warnings; provide a `--strict` flag that fails on any issue.

---

## D12: Internal Schema Objects Before JSON Output

**Decision**: Build internal PHP objects representing OpenAPI structures, then serialize to JSON.

**Rationale**:
- Type safety catches errors at compile time
- Objects can validate themselves
- Easier to manipulate (add/remove/modify)
- Can implement JsonSerializable for clean output

**Trade-offs**:
- (+) Type safety and IDE support
- (+) Validation before output
- (+) Easier testing
- (-) More code than simple arrays
- (-) Slight memory overhead

---

## D13: Schema Registry for $ref Management

**Decision**: Use a central registry (Components) to manage component references.

**Rationale**:
- OpenAPI encourages reusable schemas via $ref
- Without central management, we'd duplicate schemas
- Registry can detect circular references
- Simplifies generator code
- `ShouldBeReferenced` interface marks factories that should become components

**Trade-offs**:
- (+) DRY specs
- (+) Smaller output files
- (+) Handles circular refs
- (-) Additional complexity
- (-) Must ensure unique names

---

## D14: Leverage Open-Source, Maintain Clear Boundaries

**Decision**: Depend on open-source tools (Scribe, laravel-rules-to-schema, nikic/php-parser, etc.) but maintain clear dependency boundaries in code.

**Rationale**:
- Don't reinvent the wheel — use battle-tested open-source implementations
- Wrap external dependencies behind our own interfaces
- Swappable without affecting consumers
- Study their patterns AND use their code

**Boundaries**:
- External dependencies should be wrapped/abstracted at integration points
- No direct Scribe/external types in our public API
- Internal code depends on our interfaces, not external implementations

**What to Leverage**:
- Scribe: FormRequest extraction, validation rule parsing (already used via `RuleExtractor`)
- laravel-rules-to-schema: Base rule-to-schema conversion (already used via `RuleToSchema`)
- nikic/php-parser: AST analysis for JsonResource and migration parsing (F4, F5)

---

## Future Platform Decisions

### D15: Stoplight Elements for Documentation UI

**Decision**: Use Stoplight Elements (React) instead of Swagger UI or custom build for the hosted docs platform.

**Rationale**:
- Modern, clean design (Swagger UI looks dated)
- Active development and maintenance
- MIT licensed
- "Try It Out" functionality built-in
- Responsive design

**Alternatives Considered**:
- Swagger UI: Dated appearance, heavier bundle
- Redoc: Beautiful but read-only (no "Try It Out")
- Custom build: 2-3 months of work, maintenance burden

---

### D16: Containerized Analysis for Security

**Decision**: Run user code analysis in isolated Docker containers.

**Rationale**:
- Users' composer.json may have install scripts
- Code analysis requires loading PHP files
- We can't trust arbitrary user code
- Containers provide strong isolation

**Container Constraints**:
```yaml
read_only: true        # Can't write to filesystem
network_mode: none     # No network access
mem_limit: 512m        # Memory limit
cpus: 0.5             # CPU limit
timeout: 300s          # 5 minute max
```

---

### D17: PostgreSQL Over MySQL

**Decision**: Use PostgreSQL as the primary database for the platform.

**Rationale**:
- Native JSONB support (better for storing OpenAPI specs)
- Better performance for complex queries
- More advanced features (CTEs, window functions)

---

### D18: Webhook-First Architecture

**Decision**: Design the build system around webhooks as the primary trigger.

**Rationale**:
- Enables "living documentation" value proposition
- Matches developer workflow (push → docs update)
- More reliable than polling
- Industry standard for CI/CD

**Flow**:
```
GitHub Push → Webhook → Queue Job → Container → Build → Update Docs
```

---

### D19: Subdomain-Based Project Routing

**Decision**: Each project gets a subdomain (`project-slug.laragen.com`) rather than path-based routing.

**Rationale**:
- Cleaner URLs for users
- Better separation (cookies, caching)
- Easier custom domain support later
- Professional appearance

---

### D20: Inertia.js Over Livewire

**Decision**: Use Inertia.js with React for the dashboard, not Livewire.

**Rationale**:
- Stoplight Elements is React-based
- Better TypeScript support
- More flexible for complex UI
- SPA-like experience with SSR

---

### D21: Stripe via Laravel Cashier

**Decision**: Use Stripe for payments via Laravel Cashier.

**Rationale**:
- First-party Laravel integration
- Handles subscriptions, trials, webhooks
- Stripe is industry standard

**Pricing Tiers**:
- Solo: $19/mo (3 projects)
- Team: $49/mo (10 projects)
- Agency: $99/mo (25 projects)
- Enterprise: $249/mo (unlimited)

---

## Open Questions (To Decide Later)

### Q1: How to handle Livewire/Inertia responses?
- These aren't traditional JSON APIs
- May need to skip or provide special handling
- Decision: Skip for MVP, add support based on demand

### Q2: Should we support API versioning?
- Some APIs use /v1/, /v2/ prefixes
- Some use headers for versioning
- Decision: Support path-based versioning in MVP, headers later

### Q3: How to handle authentication in "Try It Out"?
- Users need to provide tokens to test endpoints
- Security implications of storing tokens
- Decision: Client-side only storage, clear warnings

### Q4: Rate limiting for free tier?
- How many builds per month?
- How many projects?
- Decision: Free tier = 3 projects, 50 builds/month
