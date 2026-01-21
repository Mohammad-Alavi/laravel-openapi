<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use Webmozart\Assert\Assert;

/**
 * Name field used across multiple OpenAPI objects.
 *
 * Used for identifying names in Contact, License, Tag, and other objects.
 * The value cannot be empty.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#contact-object
 * @see https://spec.openapis.org/oas/v3.2.0#license-object
 * @see https://spec.openapis.org/oas/v3.2.0#tag-object
 */
final readonly class Name extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Assert::notEmpty($value);
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
