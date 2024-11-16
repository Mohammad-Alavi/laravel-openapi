<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Keyword;

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

    /** @return string[] */
    public function value(): array
    {
        return $this->properties;
    }
}
