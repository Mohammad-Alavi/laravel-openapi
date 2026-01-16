<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "uniqueItems" keyword requires array items to be unique.
 *
 * When true, all items in the array must be distinct from each other.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-6.4.3
 */
final readonly class UniqueItems implements Keyword
{
    private function __construct(
        private bool $value,
    ) {
    }

    public static function create(bool $value): self
    {
        return new self($value);
    }

    public static function name(): string
    {
        return 'uniqueItems';
    }

    public function jsonSerialize(): bool
    {
        return $this->value();
    }

    public function value(): bool
    {
        return $this->value;
    }
}
