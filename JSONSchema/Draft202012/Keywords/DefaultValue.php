<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class DefaultValue implements Keyword
{
    private function __construct(
        private mixed $value,
    ) {
    }

    public static function create(mixed $value): self
    {
        return new self($value);
    }

    public static function name(): string
    {
        return 'default';
    }

    public function jsonSerialize(): mixed
    {
        return $this->value();
    }

    public function value(): mixed
    {
        return $this->value;
    }
}
