<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "required" keyword specifies which properties must be present in an object.
 *
 * Lists property names that the object instance must contain.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-6.5.3
 */
final readonly class Required implements Keyword
{
    private function __construct(
        private array $properties,
    ) {
    }

    public static function create(string ...$property): self
    {
        return new self($property);
    }

    public static function name(): string
    {
        return 'required';
    }

    public function jsonSerialize(): array
    {
        return $this->value();
    }

    /** @return string[] */
    public function value(): array
    {
        return $this->properties;
    }
}
