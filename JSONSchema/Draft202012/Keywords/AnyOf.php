<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

/**
 * The "anyOf" keyword validates that an instance matches AT LEAST ONE of the given schemas.
 *
 * At least one subschema must validate for the instance to be valid (logical OR).
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.2.1.2
 */
final readonly class AnyOf implements Keyword
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
        return 'anyOf';
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
