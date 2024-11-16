<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface\Keyword;

final readonly class Examples implements Keyword
{
    private function __construct(
        private array $examples,
    ) {
    }

    public static function create(mixed ...$example): self
    {
        return new self($example);
    }

    public static function name(): string
    {
        return 'examples';
    }

    public function value(): array
    {
        return $this->examples;
    }
}
