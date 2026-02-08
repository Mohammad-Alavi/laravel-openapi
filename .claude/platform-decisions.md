# Laragen Technical Decisions

Decisions specific to the Laragen package layer.

---

## LD1: Extend, Don't Replace LaravelOpenApi

**Decision**: Laragen extends the LaravelOpenApi generators and builders rather than replacing them.

**Rationale**:
- LaravelOpenApi provides the solid foundation (Generator, Builders, Attributes)
- Laragen adds automatic analysis on top
- Users who want manual control use LaravelOpenApi directly
- Users who want zero-config use Laragen

---

## LD2: Configurable Rule-to-Schema Mapping

**Decision**: Validation rule → JSON Schema mapping is configurable via `config/rules-to-schema.php`.

**Rationale**:
- Laravel validation rules don't map 1:1 to JSON Schema
- Custom validation rules need custom mappings
- Configuration allows users to override defaults
- New rules can be supported without code changes

---

## LD3: Custom RuleParsers for Complex Rules

**Decision**: Complex validation rules get dedicated parser classes rather than simple config mappings.

**Examples**: `PasswordParser`, `RequiredWithoutParser`

**Rationale**:
- Some rules (like `password`) have complex interactions with schema output
- Conditional rules (`required_without`) need context about other fields
- Parsers can handle edge cases that simple mapping cannot
- Leverages open-source base (per D14) with custom extensions on top

---

## LD4: Example Generation from Schema

**Decision**: Auto-generate example values from JSON Schema definitions.

**Rationale**:
- Good examples improve API documentation quality
- Manual example writing is tedious and error-prone
- Schema constraints (type, format, enum, min/max) provide enough info to generate realistic values
- `ExampleOverride` allows manual overrides when auto-generation isn't sufficient

---

## Future Platform Decisions

### LD5: Containerized Analysis for Security

**Decision**: Run user code analysis in isolated Docker containers.

**Rationale**:
- Users' composer.json may have install scripts
- Code analysis requires loading PHP files
- We can't trust arbitrary user code
- Containers provide strong isolation

**Implementation**: Clone repo → Docker container → composer install → openapi:generate → capture output → cleanup

---

### LD6: Webhook-First Architecture

**Decision**: Primary sync mechanism is GitHub/GitLab webhooks.

**Rationale**:
- Real-time updates (push → docs update)
- No polling overhead
- Industry standard
- Unique differentiator (no competitor has this)

---

### LD7: Stoplight Elements for Documentation UI

**Decision**: Use Stoplight Elements (React) for hosted documentation.

**Rationale**:
- Modern, well-maintained
- MIT licensed
- "Try it out" functionality
- Good mobile support
- Active community

**Alternatives Considered**:
- Swagger UI — Dated look
- Redoc — Good but less interactive
- Custom — Too much work

---

### LD8: PostgreSQL over MySQL

**Decision**: Use PostgreSQL for the platform database.

**Rationale**:
- Better JSONB support (storing OpenAPI specs)
- Better array handling
- Richer query capabilities

---

### LD9: Subdomain-Based Routing

**Decision**: Each project gets `{project-slug}.laragen.com`.

**Rationale**:
- Clean URLs
- Easy custom domain support
- Standard pattern (GitHub Pages, Netlify, etc.)

---

### LD10: Inertia.js for Platform Dashboard

**Decision**: Use Inertia.js + React for the dashboard.

**Rationale**:
- Consistent with docs UI (React/Stoplight Elements)
- Laravel-native feel
- No separate API needed

---

## Open Questions

### Q1: How to handle Livewire/Inertia responses?
**Status**: Research needed
**Options**:
1. Skip them (not API responses)
2. Detect and warn user
3. Document as HTML response

### Q2: API versioning support?
**Status**: Defer to v2
**Consideration**: `/v1/users` vs `/api/v1/users` patterns

### Q3: "Try It Out" authentication in hosted docs?
**Status**: Design needed
**Options**:
1. User provides token manually
2. OAuth flow in docs
3. Test tokens from dashboard

### Q4: Free tier rate limits?
**Status**: Business decision
**Consideration**: Balance generous free tier vs sustainability
