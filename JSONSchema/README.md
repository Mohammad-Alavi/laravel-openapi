# Object-Oriented JSON Schema

A PHP implementation for building JSON Schema Draft 2020-12 schemas using a fluent, type-safe API.

## Overview

This library provides:
- **Fluent Schema Builder**: Type-safe API for building JSON Schema documents
- **All 50 Keywords**: Complete implementation of Draft 2020-12 specification
- **7 Vocabularies**: Core, Applicator, Unevaluated, Validation, Meta-Data, Format-Annotation, Content
- **Extensibility**: Add custom keywords and vocabularies
- **Validation**: Vocabulary and meta-schema validation for schemas

**Important**: This library builds schemas - it does NOT validate data against schemas. Use external validators like `justinrainbow/json-schema` or `opis/json-schema` for data validation.

## Quick Start

```php
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;

// Build a user schema
$schema = StrictFluentDescriptor::object()
    ->properties(
        Property::create('name', StrictFluentDescriptor::string()->minLength(1)),
        Property::create('email', StrictFluentDescriptor::string()->format('email')),
        Property::create('age', StrictFluentDescriptor::integer()->minimum(0)),
    )
    ->required('name', 'email');

// Compile to array
$compiled = $schema->compile();
// Output: ['type' => 'object', 'properties' => [...], 'required' => ['name', 'email']]

// Or JSON encode directly
$json = json_encode($schema, JSON_PRETTY_PRINT);
```

## Descriptors

Two descriptor classes are available:

### StrictFluentDescriptor (Recommended)

Provides type-safe entry points with restricted methods based on the schema type:

```php
StrictFluentDescriptor::string()   // Returns StringRestrictor - only string methods
StrictFluentDescriptor::integer()  // Returns IntegerRestrictor - only integer/number methods
StrictFluentDescriptor::number()   // Returns NumberRestrictor
StrictFluentDescriptor::boolean()  // Returns BooleanRestrictor
StrictFluentDescriptor::array()    // Returns ArrayRestrictor
StrictFluentDescriptor::object()   // Returns ObjectRestrictor
StrictFluentDescriptor::null()     // Returns NullRestrictor
StrictFluentDescriptor::constant($value)  // Returns ConstantRestrictor
StrictFluentDescriptor::enumerator(...$values)  // Returns EnumRestrictor
```

### LooseFluentDescriptor

Provides all methods regardless of type - use when you need flexibility:

```php
$schema = LooseFluentDescriptor::withoutSchema()
    ->type('string', 'null')  // Multiple types
    ->minLength(1)
    ->maximum(100);  // All methods available
```

## Architecture

### Directory Structure

```
JSONSchema/
├── Contracts/                    # Base contracts (dialect-agnostic)
│   ├── Keyword.php              # Base keyword interface
│   └── Vocabulary.php           # Base vocabulary interface
├── Draft202012/                  # Draft 2020-12 implementation
│   ├── Contracts/
│   │   ├── Keyword.php          # Draft-specific keyword interface
│   │   ├── Descriptions/        # Fluent method interfaces
│   │   └── Restrictors/         # Type-safe restrictor interfaces
│   ├── Formats/
│   │   ├── DefinedFormat.php    # Format interface
│   │   ├── StringFormat.php     # Standard formats enum
│   │   └── CustomFormat.php     # Ad-hoc format value object
│   ├── Keywords/                # 50 keyword implementations
│   ├── Vocabularies/            # 7 vocabulary classes
│   ├── KeywordFactory.php       # Factory for creating keywords
│   ├── LooseFluentDescriptor.php
│   └── StrictFluentDescriptor.php
├── Extensions/                   # Extension examples
│   └── OpenAPI/                 # OpenAPI 3.1 extensions
├── Validation/
│   ├── Contracts/
│   │   └── FormatValidator.php
│   ├── VocabularyValidator.php  # Validates keyword names
│   └── MetaSchemaValidator.php  # Validates keyword values
├── FormatRegistry.php           # Format string registry
└── KeywordRegistry.php          # Keyword/vocabulary registry
```

### Core Components

| Component | Purpose |
|-----------|---------|
| `Keyword` | Interface for schema keywords (`type`, `maxLength`, etc.) |
| `Vocabulary` | Groups related keywords (Core, Validation, etc.) |
| `KeywordRegistry` | Tracks keywords and their vocabularies |
| `KeywordFactory` | Creates keyword instances |
| `FluentDescriptor` | Builds schemas with method chaining |

## Adding Custom Keywords

### Step 1: Create the Keyword Class

```php
<?php

namespace MyApp\Schema\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class XMyCustom implements Keyword
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    public static function name(): string
    {
        return 'x-my-custom';  // The JSON key name
    }

    public function value(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
```

### Step 2: Use the Keyword (Without Autocomplete)

Use the `set()` escape hatch on any descriptor:

```php
use MyApp\Schema\Keywords\XMyCustom;

$schema = StrictFluentDescriptor::string()
    ->minLength(1)
    ->set(XMyCustom::create('my-value'));

// Compiles to: {"type": "string", "minLength": 1, "x-my-custom": "my-value"}
```

### Step 3: Add Autocomplete (Optional)

Extend the descriptor and add a method for your keyword:

```php
<?php

namespace MyApp\Schema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;
use MyApp\Schema\Keywords\XMyCustom;

class MyDescriptor extends StrictFluentDescriptor
{
    public function xMyCustom(string $value): static
    {
        return $this->set(XMyCustom::create($value));
    }
}

// Usage with autocomplete:
$schema = MyDescriptor::string()
    ->minLength(1)
    ->xMyCustom('my-value');  // IDE autocomplete works!
```

## Creating Custom Vocabularies

### Step 1: Create Vocabulary Class

```php
<?php

namespace MyApp\Schema;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Vocabulary;
use MyApp\Schema\Keywords\XMyCustom;
use MyApp\Schema\Keywords\XAnotherKeyword;

final readonly class MyVocabulary implements Vocabulary
{
    public function id(): string
    {
        return 'https://myapp.com/schema/vocab/custom';
    }

    public function isRequired(): bool
    {
        return false;  // true if validators MUST support this vocabulary
    }

    public function keywords(): array
    {
        return [
            XMyCustom::class,
            XAnotherKeyword::class,
        ];
    }
}
```

### Step 2: Register the Vocabulary

```php
use MohammadAlavi\ObjectOrientedJSONSchema\KeywordRegistry;
use MyApp\Schema\MyVocabulary;

$registry = new KeywordRegistry();
$registry->registerVocabulary(new MyVocabulary());

// Or register multiple:
$registry->registerVocabularies(
    new CoreVocabulary(),
    new ValidationVocabulary(),
    new MyVocabulary(),
);

// Query the registry:
$registry->hasKeyword('x-my-custom');  // true
$registry->getKeywordClass('x-my-custom');  // XMyCustom::class
$registry->getVocabularyForKeyword(XMyCustom::class);  // MyVocabulary instance
```

## Using the $vocabulary Keyword

The `$vocabulary` keyword declares which vocabularies a schema uses. This is typically used in meta-schemas:

```php
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Vocabulary\Vocab;

$metaSchema = LooseFluentDescriptor::create()
    ->id('https://myapp.com/my-meta-schema')
    ->vocabulary(
        // Required vocabularies - validators MUST support these
        Vocab::create('https://json-schema.org/draft/2020-12/vocab/core', true),
        Vocab::create('https://json-schema.org/draft/2020-12/vocab/validation', true),

        // Optional vocabularies - validators MAY support these
        Vocab::create('https://json-schema.org/draft/2020-12/vocab/format-annotation', false),
        Vocab::create('https://myapp.com/schema/vocab/custom', false),
    );
```

The second parameter to `Vocab::create()` indicates:
- `true` = Required - validators MUST understand all keywords from this vocabulary
- `false` = Optional - validators that don't understand this vocabulary can still process the schema

## Validation

### VocabularyValidator

Validates that keyword **names** are from registered vocabularies:

```php
use MohammadAlavi\ObjectOrientedJSONSchema\Validation\VocabularyValidator;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Draft202012Dialect;

$dialect = new Draft202012Dialect();
$validator = new VocabularyValidator($dialect->registry());

$schema = ['type' => 'string', 'unknownKeyword' => 'value'];
$result = $validator->validate($schema);

if (!$result->isValid()) {
    foreach ($result->errors as $error) {
        echo $error->message;  // "Unknown keyword 'unknownKeyword' is not part of any registered vocabulary"
    }
}

// Check what vocabularies are used:
$result->usedVocabularies;  // ['https://json-schema.org/draft/2020-12/vocab/validation']
$result->unknownKeywords;   // ['unknownKeyword']
```

### MetaSchemaValidator

Validates that keyword **values** are correct types:

```php
use MohammadAlavi\ObjectOrientedJSONSchema\Validation\MetaSchemaValidator;

$validator = new MetaSchemaValidator();

// Invalid: type should be a string, not an array of invalid types
$schema = ['type' => 'stringg', 'maxLength' => 'five'];
$result = $validator->validate($schema);

if (!$result->isValid()) {
    foreach ($result->errors as $error) {
        echo "{$error->path}: {$error->message}\n";
        // "type: 'type' must be a valid type, got 'stringg'..."
        // "maxLength: 'maxLength' must be an integer, got string"
    }
}
```

### Combined Validation

For complete schema validation, run both validators:

```php
$schema = $myDescriptor->compile();

// 1. Validate keyword names
$vocabResult = $vocabularyValidator->validate($schema);

// 2. Validate keyword values
$metaResult = $metaSchemaValidator->validate($schema);

$isValid = $vocabResult->isValid() && $metaResult->isValid();
```

## Working with Formats

### Using Standard Formats

```php
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;

$schema = StrictFluentDescriptor::string()
    ->format(StringFormat::EMAIL);  // Enum with IDE autocomplete
```

### Using Custom Format Strings

```php
// Direct string (automatically creates CustomFormat internally):
$schema = StrictFluentDescriptor::string()
    ->format('phone-number');

// Or explicit CustomFormat:
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\CustomFormat;

$schema = StrictFluentDescriptor::string()
    ->format(CustomFormat::create('phone-number'));
```

### Creating Format Enums

```php
<?php

namespace MyApp\Schema\Formats;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;

enum MyFormats: string implements DefinedFormat
{
    case PHONE = 'phone-number';
    case SSN = 'ssn';
    case CREDIT_CARD = 'credit-card';

    public function value(): string
    {
        return $this->value;
    }
}

// Usage:
$schema = StrictFluentDescriptor::string()->format(MyFormats::PHONE);
```

### FormatRegistry

Registry for tracking and optionally validating formats:

```php
use MohammadAlavi\ObjectOrientedJSONSchema\FormatRegistry;

$registry = FormatRegistry::withStandardFormats();  // Pre-loaded with JSON Schema standard formats

// Register custom formats:
$registry->register('phone-number');  // Annotation only
$registry->registerWithCallable(
    'credit-card',
    fn($v) => preg_match('/^\d{16}$/', $v),
    'Invalid credit card number'
);

// Check formats:
$registry->isKnown('email');  // true
$registry->hasValidator('credit-card');  // true
$registry->validate('credit-card', '1234567890123456');  // true
```

## Complete Extension Example (OpenAPI)

See `JSONSchema/Extensions/OpenAPI/` for a complete example of extending JSON Schema:

### 1. Keywords

```php
// Extensions/OpenAPI/Keywords/Discriminator.php
final readonly class Discriminator implements Keyword
{
    private function __construct(
        private string $propertyName,
        private array $mapping = [],
    ) {}

    public static function create(string $propertyName, array $mapping = []): self
    {
        return new self($propertyName, $mapping);
    }

    public static function name(): string
    {
        return 'discriminator';
    }

    // ... value() and jsonSerialize() methods
}
```

### 2. Vocabulary

```php
// Extensions/OpenAPI/OpenAPIVocabulary.php
final readonly class OpenAPIVocabulary implements Vocabulary
{
    public function id(): string
    {
        return 'https://spec.openapis.org/oas/3.1/vocab/base';
    }

    public function isRequired(): bool
    {
        return false;
    }

    public function keywords(): array
    {
        return [
            Discriminator::class,
            ExternalDocs::class,
        ];
    }
}
```

### 3. Trait for Autocomplete

```php
// Extensions/OpenAPI/Concerns/HasOpenAPIKeywords.php
trait HasOpenAPIKeywords
{
    public function discriminator(string $propertyName, array $mapping = []): static
    {
        return $this->set(Discriminator::create($propertyName, $mapping));
    }

    public function externalDocs(string $url, ?string $description = null): static
    {
        return $this->set(ExternalDocs::create($url, $description));
    }
}
```

### 4. Extended Descriptor

```php
// Your application code
class OpenAPIDescriptor extends StrictFluentDescriptor
{
    use HasOpenAPIKeywords;
}

// Usage with full autocomplete:
$schema = OpenAPIDescriptor::object()
    ->properties(
        Property::create('type', OpenAPIDescriptor::string()),
    )
    ->discriminator('type', [
        'dog' => '#/components/schemas/Dog',
        'cat' => '#/components/schemas/Cat',
    ])
    ->externalDocs('https://example.com/docs', 'API Documentation');
```

## What This Library Does NOT Do

By design, this library focuses on schema building. The following are intentionally not implemented:

| Feature | Reason |
|---------|--------|
| **Data Validation** | Use `justinrainbow/json-schema` or `opis/json-schema` |
| **Schema Parsing** | Parse JSON strings to schema objects |
| **$ref Resolution** | Resolve references to actual schemas |
| **Remote Schema Fetching** | Fetch schemas from URLs |

## API Reference

### Validation Keywords

| Method | Type | Description |
|--------|------|-------------|
| `type(...)` | string/array | Restrict value types |
| `const($value)` | any | Exact value match |
| `enum(...$values)` | any | Value from list |
| `minimum($n)` | number | Minimum value |
| `maximum($n)` | number | Maximum value |
| `exclusiveMinimum($n)` | number | Exclusive minimum |
| `exclusiveMaximum($n)` | number | Exclusive maximum |
| `multipleOf($n)` | number | Must be multiple of |
| `minLength($n)` | string | Minimum length |
| `maxLength($n)` | string | Maximum length |
| `pattern($regex)` | string | Regex pattern |
| `minItems($n)` | array | Minimum items |
| `maxItems($n)` | array | Maximum items |
| `uniqueItems()` | array | All items unique |
| `minProperties($n)` | object | Minimum properties |
| `maxProperties($n)` | object | Maximum properties |
| `required(...$props)` | object | Required properties |

### Applicator Keywords

| Method | Description |
|--------|-------------|
| `properties(Property...)` | Define object properties |
| `additionalProperties($schema)` | Schema for additional properties |
| `patternProperties(PatternProperty...)` | Properties matching patterns |
| `items($schema)` | Schema for array items |
| `prefixItems(Schema...)` | Schemas for tuple positions |
| `contains($schema)` | Array must contain matching item |
| `allOf(Schema...)` | Must match all schemas |
| `anyOf(Schema...)` | Must match at least one |
| `oneOf(Schema...)` | Must match exactly one |
| `not($schema)` | Must not match |
| `if($schema)` | Conditional: if |
| `then($schema)` | Conditional: then |
| `else($schema)` | Conditional: else |

### Meta-Data Keywords

| Method | Description |
|--------|-------------|
| `title($string)` | Schema title |
| `description($string)` | Schema description |
| `default($value)` | Default value |
| `deprecated()` | Mark as deprecated |
| `readOnly()` | Read-only property |
| `writeOnly()` | Write-only property |
| `examples(...$values)` | Example values |

### Core Keywords

| Method | Description |
|--------|-------------|
| `schema($uri)` | $schema URI |
| `id($uri)` | $id identifier |
| `ref($uri)` | $ref reference |
| `anchor($name)` | $anchor |
| `defs(Def...)` | $defs definitions |
| `comment($string)` | $comment |
| `vocabulary(Vocab...)` | $vocabulary |

## License

MIT License
