<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "$id" keyword identifies a schema resource with its canonical URI.
 *
 * Establishes a base URI for resolving other URIs within the schema.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-8.2.1
 */
final readonly class Id implements Keyword
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
        return '$id';
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
