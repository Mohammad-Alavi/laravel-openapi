<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class IsWriteOnly implements Keyword
{
    private function __construct(
        private bool $value,
    ) {
    }

    public static function create(bool $value): self
    {
        return new self($value);
    }

    public static function name(): string
    {
        return 'writeOnly';
    }

    public function jsonSerialize(): bool
    {
        return $this->value();
    }

    public function value(): bool
    {
        return $this->value;
    }
}
