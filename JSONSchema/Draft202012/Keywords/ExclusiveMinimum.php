<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

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
