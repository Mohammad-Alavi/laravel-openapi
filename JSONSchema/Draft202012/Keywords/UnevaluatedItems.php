<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "unevaluatedItems" keyword validates array items not evaluated by other keywords.
 *
 * Applies to items not covered by "prefixItems", "items", "contains", or subschemas.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-11.2
 */
final readonly class UnevaluatedItems implements Keyword
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
        return 'unevaluatedItems';
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
