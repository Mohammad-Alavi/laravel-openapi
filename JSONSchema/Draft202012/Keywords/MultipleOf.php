<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "multipleOf" keyword restricts a number to be a multiple of a given value.
 *
 * The numeric instance must be divisible by this value with no remainder.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-6.2.1
 */
final readonly class MultipleOf implements Keyword
{
    private function __construct(
        private float $value,
    ) {
    }

    public static function create(float $value): self
    {
        return new self($value);
    }

    public static function name(): string
    {
        return 'multipleOf';
    }

    public function jsonSerialize(): float
    {
        return $this->value();
    }

    public function value(): float
    {
        return $this->value;
    }
}
