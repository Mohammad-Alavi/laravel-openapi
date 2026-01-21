<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;

/**
 * Version of the API.
 *
 * The version of the OpenAPI document (which is distinct from the OpenAPI
 * Specification version or the API implementation version). This is a
 * REQUIRED field in the Info Object.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#info-object
 */
final readonly class Version extends StringField
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
