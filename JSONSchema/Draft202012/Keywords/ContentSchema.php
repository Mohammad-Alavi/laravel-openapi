<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

/**
 * The "contentSchema" keyword defines the schema for decoded string content.
 *
 * Used with contentMediaType and contentEncoding to validate decoded content.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-8.5
 */
final readonly class ContentSchema implements Keyword
{
    private function __construct(
        private LooseFluentDescriptor $schema,
    ) {
    }

    public static function create(JSONSchema $schema): self
    {
        return new self($schema);
    }

    public static function name(): string
    {
        return 'contentSchema';
    }

    public function jsonSerialize(): JSONSchema
    {
        return $this->value();
    }

    public function value(): LooseFluentDescriptor
    {
        return $this->schema;
    }
}
