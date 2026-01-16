<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "additionalProperties" keyword validates properties not covered by other keywords.
 *
 * Applies to properties not matched by "properties" or "patternProperties".
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.3.2.3
 */
final readonly class AdditionalProperties implements Keyword
{
    private function __construct(
        private JSONSchema|bool $schema,
    ) {
    }

    public static function create(JSONSchema|bool $schema): self
    {
        return new self($schema);
    }

    public static function name(): string
    {
        return 'additionalProperties';
    }

    public function jsonSerialize(): JSONSchema|bool
    {
        return $this->value();
    }

    public function value(): JSONSchema|bool
    {
        return $this->schema;
    }
}
