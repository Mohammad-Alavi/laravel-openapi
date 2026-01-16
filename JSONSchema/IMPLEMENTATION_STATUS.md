# JSON Schema Draft 2020-12 Implementation Status

## Keywords by Vocabulary

### Core Vocabulary (`https://json-schema.org/draft/2020-12/vocab/core`)
| Keyword | Status | File |
|---------|--------|------|
| `$id` | ✅ Implemented | `Keywords/Id.php` |
| `$schema` | ✅ Implemented | `Keywords/Schema.php` |
| `$ref` | ✅ Implemented | `Keywords/Ref.php` |
| `$anchor` | ✅ Implemented | `Keywords/Anchor.php` |
| `$dynamicRef` | ✅ Implemented | `Keywords/DynamicRef.php` |
| `$dynamicAnchor` | ✅ Implemented | `Keywords/DynamicAnchor.php` |
| `$vocabulary` | ✅ Implemented | `Keywords/Vocabulary/Vocabulary.php` |
| `$comment` | ✅ Implemented | `Keywords/Comment.php` |
| `$defs` | ✅ Implemented | `Keywords/Defs/Defs.php` |

### Applicator Vocabulary (`https://json-schema.org/draft/2020-12/vocab/applicator`)
| Keyword | Status | File |
|---------|--------|------|
| `prefixItems` | ✅ Implemented | `Keywords/PrefixItems.php` |
| `items` | ✅ Implemented | `Keywords/Items.php` |
| `contains` | ✅ Implemented | `Keywords/Contains.php` |
| `additionalProperties` | ✅ Implemented | `Keywords/AdditionalProperties.php` |
| `properties` | ✅ Implemented | `Keywords/Properties/Properties.php` |
| `patternProperties` | ✅ Implemented | `Keywords/PatternProperties/PatternProperties.php` |
| `dependentSchemas` | ✅ Implemented | `Keywords/DependentSchemas/DependentSchemas.php` |
| `propertyNames` | ✅ Implemented | `Keywords/PropertyNames.php` |
| `if` | ✅ Implemented | `Keywords/IfKeyword.php` |
| `then` | ✅ Implemented | `Keywords/Then.php` |
| `else` | ✅ Implemented | `Keywords/ElseKeyword.php` |
| `allOf` | ✅ Implemented | `Keywords/AllOf.php` |
| `anyOf` | ✅ Implemented | `Keywords/AnyOf.php` |
| `oneOf` | ✅ Implemented | `Keywords/OneOf.php` |
| `not` | ✅ Implemented | `Keywords/Not.php` |

### Unevaluated Vocabulary (`https://json-schema.org/draft/2020-12/vocab/unevaluated`)
| Keyword | Status | File |
|---------|--------|------|
| `unevaluatedItems` | ✅ Implemented | `Keywords/UnevaluatedItems.php` |
| `unevaluatedProperties` | ✅ Implemented | `Keywords/UnevaluatedProperties.php` |

### Validation Vocabulary (`https://json-schema.org/draft/2020-12/vocab/validation`)
| Keyword | Status | File |
|---------|--------|------|
| `type` | ✅ Implemented | `Keywords/Type.php` |
| `const` | ✅ Implemented | `Keywords/Constant.php` |
| `enum` | ✅ Implemented | `Keywords/Enum.php` |
| `multipleOf` | ✅ Implemented | `Keywords/MultipleOf.php` |
| `maximum` | ✅ Implemented | `Keywords/Maximum.php` |
| `exclusiveMaximum` | ✅ Implemented | `Keywords/ExclusiveMaximum.php` |
| `minimum` | ✅ Implemented | `Keywords/Minimum.php` |
| `exclusiveMinimum` | ✅ Implemented | `Keywords/ExclusiveMinimum.php` |
| `maxLength` | ✅ Implemented | `Keywords/MaxLength.php` |
| `minLength` | ✅ Implemented | `Keywords/MinLength.php` |
| `pattern` | ✅ Implemented | `Keywords/Pattern.php` |
| `maxItems` | ✅ Implemented | `Keywords/MaxItems.php` |
| `minItems` | ✅ Implemented | `Keywords/MinItems.php` |
| `uniqueItems` | ✅ Implemented | `Keywords/UniqueItems.php` |
| `maxContains` | ✅ Implemented | `Keywords/MaxContains.php` |
| `minContains` | ✅ Implemented | `Keywords/MinContains.php` |
| `maxProperties` | ✅ Implemented | `Keywords/MaxProperties.php` |
| `minProperties` | ✅ Implemented | `Keywords/MinProperties.php` |
| `required` | ✅ Implemented | `Keywords/Required.php` |
| `dependentRequired` | ✅ Implemented | `Keywords/DependentRequired/DependentRequired.php` |

### Meta-Data Vocabulary (`https://json-schema.org/draft/2020-12/vocab/meta-data`)
| Keyword | Status | File |
|---------|--------|------|
| `title` | ✅ Implemented | `Keywords/Title.php` |
| `description` | ✅ Implemented | `Keywords/Description.php` |
| `default` | ✅ Implemented | `Keywords/DefaultValue.php` |
| `deprecated` | ✅ Implemented | `Keywords/Deprecated.php` |
| `readOnly` | ✅ Implemented | `Keywords/IsReadOnly.php` |
| `writeOnly` | ✅ Implemented | `Keywords/IsWriteOnly.php` |
| `examples` | ✅ Implemented | `Keywords/Examples.php` |

### Format-Annotation Vocabulary (`https://json-schema.org/draft/2020-12/vocab/format-annotation`)
| Keyword | Status | File |
|---------|--------|------|
| `format` | ✅ Implemented | `Keywords/Format.php` |

### Content Vocabulary (`https://json-schema.org/draft/2020-12/vocab/content`)
| Keyword | Status | File |
|---------|--------|------|
| `contentEncoding` | ✅ Implemented | `Keywords/ContentEncoding.php` |
| `contentMediaType` | ✅ Implemented | `Keywords/ContentMediaType.php` |
| `contentSchema` | ✅ Implemented | `Keywords/ContentSchema.php` |

---

## Infrastructure Status

| Component | Status | Description |
|-----------|--------|-------------|
| Base Keyword Contract | ✅ Implemented | `Contracts/Keyword.php` |
| Base Vocabulary Contract | ✅ Implemented | `Contracts/Vocabulary.php` |
| Draft202012 Keyword Contract | ✅ Implemented | `Draft202012/Contracts/Keyword.php` |
| KeywordRegistry | ✅ Implemented | `KeywordRegistry.php` |
| Draft202012Dialect | ✅ Implemented | `Draft202012/Draft202012Dialect.php` |
| KeywordFactory | ✅ Implemented | `Draft202012/KeywordFactory.php` |
| Boolean Schemas | ✅ Implemented | `Draft202012/BooleanSchema.php` |
| Schema Building | ✅ Implemented | `Draft202012/LooseFluentDescriptor.php` |
| Vocabulary Classes | ✅ Implemented | `Draft202012/Vocabularies/*.php` |
| FormatRegistry | ✅ Implemented | `FormatRegistry.php` |

---

## Validation Status

| Component | Status | Description |
|-----------|--------|-------------|
| VocabularyValidator | ✅ Implemented | Validates keyword names against vocabularies |
| MetaSchemaValidator | ✅ Implemented | Validates keyword values against meta-schema |
| FormatValidator Contract | ✅ Implemented | Contract for custom format validators |

---

## Directory Structure

```
JSONSchema/
├── Contracts/                    # Base contracts (dialect-agnostic)
│   ├── Keyword.php
│   └── Vocabulary.php
├── Draft202012/                  # Draft 2020-12 implementation
│   ├── Concerns/
│   │   └── HasGetters.php
│   ├── Contracts/
│   │   ├── Descriptions/        # Fluent method interfaces
│   │   └── Restrictors/         # Type-safe restrictor interfaces
│   ├── Formats/
│   │   ├── CustomFormat.php
│   │   ├── DefinedFormat.php
│   │   └── StringFormat.php
│   ├── Keywords/                # All 50 keyword classes
│   ├── Vocabularies/            # 7 vocabulary classes
│   ├── BooleanSchema.php
│   ├── Draft202012Dialect.php
│   ├── KeywordFactory.php
│   ├── LooseFluentDescriptor.php
│   └── StrictFluentDescriptor.php
├── Extensions/
│   └── OpenAPI/                 # OpenAPI extension example
├── Validation/
│   ├── Contracts/
│   │   └── FormatValidator.php
│   ├── MetaSchemaValidationError.php
│   ├── MetaSchemaValidationResult.php
│   ├── MetaSchemaValidator.php
│   ├── VocabularyValidationError.php
│   ├── VocabularyValidationResult.php
│   └── VocabularyValidator.php
├── FormatRegistry.php
└── KeywordRegistry.php
```

---

## Summary

**Keywords:** 50/50 implemented (100%)
- All keywords from JSON Schema Draft 2020-12 are implemented

**Vocabularies:** 7/7 implemented (100%)
- Core, Applicator, Unevaluated, Validation, Meta-Data, Format-Annotation, Content

**Infrastructure:** Complete
- KeywordRegistry, Draft202012Dialect, KeywordFactory, Boolean Schemas, all contracts

**Validation:** Complete
- VocabularyValidator: Validates keyword names against registered vocabularies
- MetaSchemaValidator: Validates keyword values against JSON Schema meta-schema

**Not Implemented (By Design):**
- Data validation (validating data against schemas) - use external validators
- Schema parsing (reading JSON string into schema objects)
- $ref resolution (resolving references to actual schemas)
- Remote schema fetching (fetching schemas from URLs)
