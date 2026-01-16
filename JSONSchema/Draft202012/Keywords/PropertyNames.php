<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

/**
 * The "propertyNames" keyword validates all property names against a schema.
 *
 * Every property name in the object must validate against the given schema.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.3.2.4
 */
final readonly class PropertyNames implements Keyword
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
        return 'propertyNames';
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
