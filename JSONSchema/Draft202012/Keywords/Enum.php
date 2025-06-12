<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class Enum implements Keyword
{
    private function __construct(
        private array $values,
    ) {
    }

    // TODO: It would be cool if enums could accept Constant or/and Schema types
    public static function create(mixed ...$value): self
    {
        return new self($value);
    }

    public static function name(): string
    {
        return 'enum';
    }

    public function jsonSerialize(): array
    {
        return $this->value();
    }

    public function value(): array
    {
        return $this->values;
    }
}
