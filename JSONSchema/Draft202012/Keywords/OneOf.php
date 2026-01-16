<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

/**
 * The "oneOf" keyword validates that an instance matches EXACTLY ONE of the given schemas.
 *
 * Exactly one subschema must validate for the instance to be valid (logical XOR).
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.2.1.3
 */
final readonly class OneOf implements Keyword
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
        return 'oneOf';
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
