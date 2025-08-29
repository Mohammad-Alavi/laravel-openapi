<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

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
