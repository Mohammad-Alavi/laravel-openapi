<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\StringField;

final readonly class Summary extends StringField
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
