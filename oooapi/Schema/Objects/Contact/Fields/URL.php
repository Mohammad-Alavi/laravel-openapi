<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Contact\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Rules\URI;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;

final readonly class URL extends StringField
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
