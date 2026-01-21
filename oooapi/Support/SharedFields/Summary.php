<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;

/**
 * Summary field used across multiple OpenAPI objects.
 *
 * A short summary providing a brief overview. Unlike description,
 * summary is intended for quick reference and should be concise.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#info-object
 * @see https://spec.openapis.org/oas/v3.2.0#operation-object
 */
final readonly class Summary extends StringField
{
    private function __construct(
        private string $value,
    ) {
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
