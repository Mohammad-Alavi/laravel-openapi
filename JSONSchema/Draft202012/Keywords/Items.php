<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

/**
 * The "items" keyword validates array items not covered by "prefixItems".
 *
 * Applies to all items beyond those validated by "prefixItems", or all items if absent.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.3.1.2
 */
final readonly class Items implements Keyword
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
        return 'items';
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
