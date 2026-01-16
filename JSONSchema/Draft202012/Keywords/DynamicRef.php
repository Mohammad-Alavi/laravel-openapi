<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "$dynamicRef" keyword references a dynamic anchor that can be overridden.
 *
 * Used with "$dynamicAnchor" for extensible recursive schemas.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-8.2.3.2
 */
final readonly class DynamicRef implements Keyword
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
        return '$dynamicRef';
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
