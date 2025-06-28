<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;

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
