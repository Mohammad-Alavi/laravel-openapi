<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;

final readonly class Prefix extends StringField
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
