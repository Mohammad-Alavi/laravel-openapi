<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\License\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;

final readonly class Identifier extends StringField
{
    private function __construct(
        private string $value,
    ) {
        // TODO: Add validation for the identifier format.
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
