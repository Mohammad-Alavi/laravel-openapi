<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "exclusiveMaximum" keyword sets an exclusive upper limit for numeric values.
 *
 * The numeric instance must be strictly less than this value.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-6.2.3
 */
final readonly class ExclusiveMaximum implements Keyword
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
        return 'exclusiveMaximum';
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
