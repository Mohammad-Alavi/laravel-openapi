<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

/**
 * The "prefixItems" keyword validates array items by position (tuple validation).
 *
 * Each schema applies to the array item at the corresponding index.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-core#section-10.3.1.1
 */
final readonly class PrefixItems implements Keyword
{
    /** @param JSONSchema[] $schemas */
    private function __construct(
        private array $schemas,
    ) {
    }

    public static function create(JSONSchema ...$schema): self
    {
        return new self($schema);
    }

    public static function name(): string
    {
        return 'prefixItems';
    }

    public function jsonSerialize(): array
    {
        return $this->value();
    }

    /**
     * @return JSONSchema[]
     */
    public function value(): array
    {
        return $this->schemas;
    }
}
