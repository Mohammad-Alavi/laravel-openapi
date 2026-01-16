<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "const" keyword restricts an instance to a single exact value.
 *
 * The instance must be equal to the given value (using JSON equality).
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-6.1.3
 */
final readonly class Constant implements Keyword
{
    private function __construct(
        private mixed $value,
    ) {
    }

    // TODO: It would be cool if constants could accept Schema types
    public static function create(mixed $value): self
    {
        return new self($value);
    }

    public static function name(): string
    {
        return 'const';
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
