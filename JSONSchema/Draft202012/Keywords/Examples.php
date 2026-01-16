<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "examples" keyword provides sample values that validate against the schema.
 *
 * Used for documentation to illustrate valid instances.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-9.5
 */
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

    public function jsonSerialize(): array
    {
        return $this->value();
    }

    public function value(): array
    {
        return $this->examples;
    }
}
