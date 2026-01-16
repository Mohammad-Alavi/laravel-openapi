<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "$dynamicAnchor" keyword creates an overridable anchor for recursive schemas.
 *
 * Works with "$dynamicRef" to allow extending recursive schemas.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-8.2.2
 */
final readonly class DynamicAnchor implements Keyword
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
        return '$dynamicAnchor';
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
