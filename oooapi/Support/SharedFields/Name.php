<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use Webmozart\Assert\Assert;

final readonly class Name extends StringField
{
    private function __construct(
        private string $value,
    ) {
        Assert::notEmpty($value);
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
