<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "$anchor" keyword creates a plain name fragment for referencing a subschema.
 *
 * Allows referencing via URI fragment like "#myAnchor" instead of JSON Pointer.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-8.2.2
 */
final readonly class Anchor implements Keyword
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
        return '$anchor';
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
