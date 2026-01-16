<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "$comment" keyword provides a location for schema authors to add comments.
 *
 * Intended for notes to schema maintainers; ignored by validators.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-8.3
 */
final readonly class Comment implements Keyword
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
        return '$comment';
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
