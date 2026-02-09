# Fork Management Strategy

## Current Packages

| Package | Original | Fork (Mohammad-Alavi) | Status |
|---------|----------|----------------------|--------|
| `riley19280/laravel-rules-to-schema` | [GitHub](https://github.com/riley19280/laravel-rules-to-schema) | Forked | Using original via Composer |
| `riley19280/fluent-json-schema` | [GitHub](https://github.com/riley19280/fluent-json-schema) | Forked | Using original via Composer |

## Current Approach: Extension via Config

The current strategy extends the vendor packages through configuration rather than forking:

1. **Custom parsers** registered in `config/rules-to-schema.php` run alongside vendor parsers
2. **ContextAwareRuleParser** interface extends the vendor `RuleParser` contract with context injection
3. **RuleToSchema** overrides the vendor's transform pipeline to support context-aware parsing

This keeps us on the upstream release train while adding Laragen-specific capabilities.

## When to Fork

Fork only when one of these conditions is met:

1. **Upstream rejects needed PRs** — We need a feature or fix that the maintainer won't accept
2. **Breaking changes required** — Our changes would break the vendor's public API
3. **Abandoned upstream** — Maintainer stops responding to issues/PRs
4. **Performance** — We need optimizations that conflict with upstream's design goals

## Short-Term Strategy (Current)

- Continue using original packages via Composer
- Extend through Laragen's parser config system
- Contribute improvements upstream via PRs
- Keep forks up-to-date with upstream as safety net

## Medium-Term Strategy

- If upstream doesn't evolve with our needs, switch Composer to point at our forks
- Maintain backward compatibility — our forks should pass upstream's tests
- Apply minimal patches on top of upstream

## Long-Term Strategy

- **Replace FluentSchema entirely** (see `fluent-schema-replacement.md`) — eliminates `riley19280/fluent-json-schema` dependency
- **Absorb rule-to-schema logic** — Once FluentSchema is removed, the thin wrapper that `laravel-rules-to-schema` provides becomes trivial to inline
- Both packages become unnecessary once the native Schema pipeline is complete

## Risk Assessment

| Risk | Mitigation |
|------|-----------|
| Upstream breaking changes | Forks ready as fallback, pin to specific versions |
| Upstream abandonment | Forks already exist, can switch Composer source |
| Fork drift | Keep minimal delta from upstream, automate merge checks |
| Dependency on `getSchemaDTO()` | Migration plan in fluent-schema-replacement.md |
