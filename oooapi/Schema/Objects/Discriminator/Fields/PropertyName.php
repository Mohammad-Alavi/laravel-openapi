<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;

/**
 * The name of the property in the payload that will hold the discriminator value.
 *
 * This property is required in the Discriminator Object.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#discriminator-object
 */
final readonly class PropertyName extends StringField
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
