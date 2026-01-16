<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "pattern" keyword validates strings against a regular expression.
 *
 * The string must match the ECMA-262 regex pattern (partial match is sufficient).
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-6.3.3
 */
final readonly class Pattern implements Keyword
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    public static function name(): string
    {
        return 'pattern';
    }

    public function jsonSerialize(): string
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->value;
    }
}
