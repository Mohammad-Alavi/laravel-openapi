<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\OpenAPI\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;
use Webmozart\Assert\Assert;

/**
 * Represents the `jsonSchemaDialect` field in OpenAPI 3.1.
 *
 * This field specifies the dialect of JSON Schema used in the OpenAPI document.
 * It must be a valid URI.
 *
 * @see https://spec.openapis.org/oas/latest.html#specifying-schema-dialects
 */
final readonly class JsonSchemaDialect extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Assert::true(
            false !== filter_var($this->value, FILTER_VALIDATE_URL),
            'The value of jsonSchemaDialect must be a valid URI.',
        );
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    public static function v31(): self
    {
        return new self('https://spec.openapis.org/oas/3.1/dialect/base');
    }

    public function value(): string
    {
        return $this->value;
    }
}
