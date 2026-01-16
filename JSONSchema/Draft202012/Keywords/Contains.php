<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

/**
 * The "contains" keyword validates that at least one array item matches a schema.
 *
 * Combined with "minContains"/"maxContains" to control match count.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.3.1.3
 */
final readonly class Contains implements Keyword
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
        return 'contains';
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
