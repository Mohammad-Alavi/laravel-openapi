<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;

final readonly class Description extends StringField
{
    private function __construct(
        private string $value,
    ) {
        // TODO: Add validation.
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
