<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;

/**
 * Default value for a server variable.
 *
 * The default value to use for substitution, which SHALL be sent if an
 * alternate value is not supplied. This is a REQUIRED field.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#server-variable-object
 */
final readonly class DefaultValue extends StringField
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
