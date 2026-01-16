<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\PatternProperties;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

/**
 * Represents a single pattern-to-schema mapping for the "patternProperties" keyword.
 *
 * Pairs a regex pattern with a schema for validating matching properties.
 *
 * @see PatternProperties The parent keyword class
 */
final readonly class PatternProperty
{
    private function __construct(
        private string $pattern,
        private JSONSchema $schema,
    ) {
    }

    public static function create(string $pattern, JSONSchema $schema): self
    {
        return new self($pattern, $schema);
    }

    public function pattern(): string
    {
        return $this->pattern;
    }

    public function schema(): JSONSchema
    {
        return $this->schema;
    }
}
