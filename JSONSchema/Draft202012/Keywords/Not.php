<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "not" keyword validates that an instance does NOT match the given schema.
 *
 * The instance is valid if the subschema fails to validate (logical NOT).
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.2.1.4
 */
final readonly class Not implements Keyword
{
    private function __construct(
        private JSONSchema $schema,
    ) {
    }

    public static function create(JSONSchema $schema): self
    {
        return new self($schema);
    }

    public static function name(): string
    {
        return 'not';
    }

    public function jsonSerialize(): JSONSchema
    {
        return $this->value();
    }

    public function value(): JSONSchema
    {
        return $this->schema;
    }
}
