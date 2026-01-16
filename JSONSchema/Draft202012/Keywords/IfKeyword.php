<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "if" keyword provides a condition for "then"/"else" schema application.
 *
 * Used with "then" and "else" for conditional schema validation.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.2.2.1
 */
final readonly class IfKeyword implements Keyword
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
        return 'if';
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
