<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "enum" keyword restricts an instance to one of the specified values.
 *
 * The instance must equal one of the elements in the array.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-6.1.2
 */
final readonly class Enum implements Keyword
{
    private function __construct(
        private array $values,
    ) {
    }

    // TODO: It would be cool if enums could accept Constant or/and Schema types
    public static function create(mixed ...$value): self
    {
        return new self($value);
    }

    public static function name(): string
    {
        return 'enum';
    }

    public function jsonSerialize(): array
    {
        return $this->value();
    }

    public function value(): array
    {
        return $this->values;
    }
}
