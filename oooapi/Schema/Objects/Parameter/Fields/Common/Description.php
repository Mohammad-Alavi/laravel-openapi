<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;

final readonly class Description extends StringField
{
    private function __construct(
        private string $value,
    ) {
        // TODO: Add validation.
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function create(string $value): self
    {
        return new self($value);
    }
}
