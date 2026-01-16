<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "$schema" keyword declares the JSON Schema dialect used by the schema.
 *
 * Used to declare the meta-schema that a JSON Schema conforms to.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-8.1.1
 */
final readonly class Schema implements Keyword
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
        return '$schema';
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
