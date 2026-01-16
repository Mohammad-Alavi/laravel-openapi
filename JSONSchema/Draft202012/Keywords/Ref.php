<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "$ref" keyword references a schema by URI, enabling schema reuse.
 *
 * The reference is resolved against the base URI and the referenced schema is applied.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-8.2.3.1
 */
final readonly class Ref implements Keyword
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
        return '$ref';
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
