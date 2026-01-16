<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "properties" keyword defines schemas for object property validation.
 *
 * Maps property names to schemas; each property is validated against its schema.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.3.2.1
 */
final readonly class Properties implements Keyword
{
    /** @param Property[] $properties */
    private function __construct(
        private array $properties,
    ) {
    }

    public static function create(Property ...$property): self
    {
        return new self($property);
    }

    public function jsonSerialize(): array
    {
        $properties = [];
        foreach ($this->value() as $property) {
            $properties[$property->name()] = $property->schema();
        }

        return $properties;
    }

    /**
     * @return Property[]
     */
    public function value(): array
    {
        return $this->properties;
    }

    public static function name(): string
    {
        return 'properties';
    }
}
