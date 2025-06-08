<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Rules\URI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;

final readonly class SchemaURL extends StringField
{
    private function __construct(
        private string $value,
    ) {
        URI::validate($this->value);
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
