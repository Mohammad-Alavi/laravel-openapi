<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "title" keyword provides a short human-readable label for the schema.
 *
 * Used for documentation and UI generation purposes.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-9.1
 */
final readonly class Title implements Keyword
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
        return 'title';
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
