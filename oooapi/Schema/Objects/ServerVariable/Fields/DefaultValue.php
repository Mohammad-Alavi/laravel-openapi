<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;

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
