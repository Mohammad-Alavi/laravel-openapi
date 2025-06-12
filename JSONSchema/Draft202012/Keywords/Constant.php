<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class Constant implements Keyword
{
    private function __construct(
        private mixed $value,
    ) {
    }

    // TODO: It would be cool if constants could accept Schema types
    public static function create(mixed $value): self
    {
        return new self($value);
    }

    public static function name(): string
    {
        return 'const';
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
