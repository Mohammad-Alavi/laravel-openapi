<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "unevaluatedProperties" keyword validates properties not evaluated by other keywords.
 *
 * Applies to properties not covered by "properties", "patternProperties", "additionalProperties", or subschemas.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-11.3
 */
final readonly class UnevaluatedProperties implements Keyword
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
        return 'unevaluatedProperties';
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
