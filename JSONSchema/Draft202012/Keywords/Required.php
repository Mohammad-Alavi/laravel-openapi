<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class Required implements Keyword
{
    private function __construct(
        private array $properties,
    ) {
    }

    public static function create(string ...$property): self
    {
        return new self($property);
    }

    public static function name(): string
    {
        return 'required';
    }

    public function jsonSerialize(): array
    {
        return $this->value();
    }

    /** @return string[] */
    public function value(): array
    {
        return $this->properties;
    }
}
