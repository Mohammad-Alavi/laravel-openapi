<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "default" keyword provides a default value for the instance.
 *
 * Used as a hint to provide a default value when none is supplied.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-9.2
 */
final readonly class DefaultValue implements Keyword
{
    private function __construct(
        private mixed $value,
    ) {
    }

    public static function create(mixed $value): self
    {
        return new self($value);
    }

    public static function name(): string
    {
        return 'default';
    }

    public function jsonSerialize(): mixed
    {
        return $this->value();
    }

    public function value(): mixed
    {
        return $this->value;
    }
}
