<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Info\Fields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Validator;

final readonly class TermsOfService extends StringField
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
