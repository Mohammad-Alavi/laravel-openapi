<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "exclusiveMinimum" keyword sets an exclusive lower limit for numeric values.
 *
 * The numeric instance must be strictly greater than this value.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-6.2.5
 */
final readonly class ExclusiveMinimum implements Keyword
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
        return 'exclusiveMinimum';
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
