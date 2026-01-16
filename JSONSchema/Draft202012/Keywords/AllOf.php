<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

/**
 * The "allOf" keyword validates that an instance matches ALL of the given schemas.
 *
 * All subschemas must validate for the instance to be valid (logical AND).
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.2.1.1
 */
final readonly class AllOf implements Keyword
{
    /**
     * @param LooseFluentDescriptor[] $schema
     */
    private function __construct(
        private array $schema,
    ) {
    }

    public static function create(LooseFluentDescriptor ...$builder): self
    {
        return new self($builder);
    }

    public static function name(): string
    {
        return 'allOf';
    }

    /** @return LooseFluentDescriptor[] */
    public function jsonSerialize(): array
    {
        return $this->value();
    }

    /** @return LooseFluentDescriptor[] */
    public function value(): array
    {
        return $this->schema;
    }
}
