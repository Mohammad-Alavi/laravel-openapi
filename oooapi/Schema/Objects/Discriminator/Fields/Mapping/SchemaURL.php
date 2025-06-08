<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Validator;

final readonly class SchemaURL extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Validator::url($value);
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
