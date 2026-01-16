<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "then" keyword applies a schema when the "if" condition passes.
 *
 * Applied only when "if" is present and validates successfully.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.2.2.2
 */
final readonly class Then implements Keyword
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
        return 'then';
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
