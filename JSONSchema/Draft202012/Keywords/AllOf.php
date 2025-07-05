<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class AllOf implements Keyword
{
    private function __construct(
        private array $schema,
    ) {
    }

    public static function create(JSONSchema ...$builder): self
    {
        return new self($builder);
    }

    public static function name(): string
    {
        return 'allOf';
    }

    public function jsonSerialize(): array
    {
        return $this->value();
    }

    /** @return JSONSchema[] */
    public function value(): array
    {
        return $this->schema;
    }
}
