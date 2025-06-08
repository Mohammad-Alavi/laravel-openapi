<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\StringField;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Validator;

final readonly class Ref extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Validator::uri($value);
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
