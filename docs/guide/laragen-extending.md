# Extending Laragen

Laragen uses a pluggable strategy pattern for both request and response schema detection. You can add custom strategies via configuration.

## Strategy Chains

### Response Strategy Chain

Each response strategy consists of a **detector** (implements `ResponseDetector`) and a **builder** (implements `ResponseSchemaBuilder`).

Built-in order:
1. Annotation (`@response` docblock tags)
2. ResourceCollection
3. JsonResource
4. SpatieData (conditional)
5. FractalTransformer (conditional)
6. EloquentModel

### Request Strategy Chain

Each request strategy consists of a **detector** (implements `RequestDetector`) and a **builder** (implements `RequestSchemaBuilder`).

Built-in order:
1. AnnotationBodyParam (`@bodyParam` docblock tags)
2. AnnotationQueryParam (`@queryParam` docblock tags)
3. SpatieData (conditional)
4. ValidationRules (FormRequest)

## Config-Based Strategy Registration

Add custom strategies via `config/laragen.php`:

```php
'strategies' => [
    'request' => [
        'prepend' => [
            // Runs BEFORE built-in strategies (including annotations)
            [MyCustomRequestDetector::class, MyCustomRequestBuilder::class],
        ],
        'append' => [
            // Runs AFTER all built-in strategies
            [FallbackRequestDetector::class, FallbackRequestBuilder::class],
        ],
    ],
    'response' => [
        'prepend' => [
            [MyCustomResponseDetector::class, MyCustomResponseBuilder::class],
        ],
        'append' => [],
    ],
],
```

## Writing a Custom Detector/Builder Pair

### Response Strategy

```php
use MohammadAlavi\Laragen\ResponseSchema\ResponseDetector;
use MohammadAlavi\Laragen\ResponseSchema\ResponseSchemaBuilder;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

final readonly class MyResponseDetector implements ResponseDetector
{
    public function detect(string $controllerClass, string $method): mixed
    {
        // Return any non-null value to indicate this strategy handles this method.
        // The returned value is passed to the builder.
        // Return null to skip to the next strategy.
        return null;
    }
}

final readonly class MyResponseBuilder implements ResponseSchemaBuilder
{
    public function build(mixed $detected): JSONSchema
    {
        // Build and return a JSON Schema from the detected context.
        return Schema::object();
    }
}
```

### Request Strategy

```php
use Illuminate\Routing\Route;
use MohammadAlavi\Laragen\RequestSchema\RequestDetector;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaBuilder;
use MohammadAlavi\Laragen\RequestSchema\RequestSchemaResult;
use MohammadAlavi\Laragen\RequestSchema\RequestTarget;
use MohammadAlavi\Laragen\RequestSchema\ContentEncoding;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

final readonly class MyRequestDetector implements RequestDetector
{
    public function detect(Route $route, string $controllerClass, string $method): mixed
    {
        // Return any non-null value to indicate this strategy handles this route.
        // Return null to skip to the next strategy.
        return null;
    }
}

final readonly class MyRequestBuilder implements RequestSchemaBuilder
{
    public function build(mixed $detected, Route $route): RequestSchemaResult
    {
        return new RequestSchemaResult(
            schema: Schema::object(),
            target: RequestTarget::BODY,     // or RequestTarget::QUERY
            encoding: ContentEncoding::JSON,  // or ContentEncoding::MULTIPART_FORM_DATA
        );
    }
}
```

## Rule Parser Pipeline

Laragen uses custom rule parsers to handle complex Laravel validation rules. The parsers are registered in `config/rules-to-schema.php` and extend the base `laravel-rules-to-schema` conversion.

Built-in parsers:
- `PasswordParser` — handles `Password::defaults()` rule object
- `RequiredWithParser` — `required_with` using `if/then` conditions (single arg or `anyOf` for multiple)
- `RequiredWithoutParser` — `required_without` using `if: { not: { required } }` conditions
- `ConditionalRequiredParser` — `required_if` / `required_unless` conditional rules
- `ConditionalExcludeParser` — `exclude_if` / `exclude_unless` / `exclude_with` / `exclude_without`
- `ConditionalProhibitedParser` — `prohibited_if` / `prohibited_unless`
- `ConditionalAcceptedParser` — `accepted_if` / `declined_if`
- `PresentFieldParser` — `present` / `present_if` / `present_unless` / `present_with` / `present_with_all`
- `StringPatternParser` — `starts_with` / `ends_with` / `doesnt_start_with` / `doesnt_end_with` / `lowercase` / `uppercase` / `ascii` / `hex_color`
- `NumericConstraintParser` — `between` / `size` / `multiple_of` / `max_digits` / `min_digits`
- `ComparisonConstraintParser` — `gt` / `gte` / `lt` / `lte`
- `AdditionalConstraintParser` — `filled` / `distinct` / `extensions`
- `AcceptedDeclinedParser` — `accepted` / `declined`
- `NotInParser` — `not_in` using `not: { enum }`
- `FileUploadParser` — file/image/mimes rules
- `ExampleOverride` — custom example values for specific rules
- `CustomRuleDocsParser` — handles custom rules with `docs()` method

Context-aware parsers (`ContextAwareRuleParser` interface) have access to the base schema and all rules, enabling cross-field `if/then/else` logic. Regular parsers (`RuleParser`) process each field independently.

To add custom parsers, extend the `rules-to-schema.php` config with your parser classes.
