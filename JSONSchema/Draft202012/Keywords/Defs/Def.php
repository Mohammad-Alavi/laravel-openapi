<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

/**
 * Represents a single named schema definition for the "$defs" keyword.
 *
 * Pairs a name with a schema for reuse via "$ref": "#/$defs/name".
 *
 * @see Defs The parent keyword class
 */
final readonly class Def
{
    private function __construct(
        private string $name,
        private JSONSchema $schema,
    ) {
    }

    public static function create(string $name, JSONSchema $schema): self
    {
        return new self($name, $schema);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function schema(): JSONSchema
    {
        return $this->schema;
    }
}
