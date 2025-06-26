<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocumentation\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\StringField;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Validator;

final readonly class URL extends StringField
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
