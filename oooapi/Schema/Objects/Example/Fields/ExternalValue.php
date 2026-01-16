<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;

/**
 * URL pointing to the literal example.
 *
 * This provides the capability to reference examples that cannot easily
 * be included in JSON or YAML documents. The externalValue field is
 * mutually exclusive of the value field.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#example-object
 */
final readonly class ExternalValue extends StringField
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
