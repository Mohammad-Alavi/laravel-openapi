<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Validator;

/**
 * URL field used across multiple OpenAPI objects.
 *
 * Represents a URL pointing to external resources such as documentation,
 * contact pages, license information, or external documentation.
 * The value must be a valid URL format.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#contact-object
 * @see https://spec.openapis.org/oas/v3.2.0#license-object
 * @see https://spec.openapis.org/oas/v3.2.0#external-documentation-object
 */
final readonly class URL extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Validator::url($value);
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
