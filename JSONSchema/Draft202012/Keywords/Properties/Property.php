<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchemaFactory;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

/**
 * Represents a single property definition for the "properties" keyword.
 *
 * Pairs a property name with its validation schema.
 *
 * @see Properties The parent keyword class
 */
final readonly class Property
{
    private function __construct(
        private string $name,
        private LooseFluentDescriptor $schema,
    ) {
    }

    public static function create(string $name, JSONSchema|JSONSchemaFactory $schema): self
    {
        if ($schema instanceof JSONSchemaFactory) {
            $schema = $schema->build();
        }

        return new self($name, $schema);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function schema(): LooseFluentDescriptor
    {
        return $this->schema;
    }
}
