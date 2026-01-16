<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\DependentSchemas;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

/**
 * Represents a single property-to-schema mapping for the "dependentSchemas" keyword.
 *
 * Pairs a property name with a schema that must validate when the property exists.
 *
 * @see DependentSchemas The parent keyword class
 */
final readonly class DependentSchema
{
    private function __construct(
        private string $property,
        private JSONSchema $schema,
    ) {
    }

    public static function create(string $property, JSONSchema $schema): self
    {
        return new self($property, $schema);
    }

    public function property(): string
    {
        return $this->property;
    }

    public function schema(): JSONSchema
    {
        return $this->schema;
    }
}
