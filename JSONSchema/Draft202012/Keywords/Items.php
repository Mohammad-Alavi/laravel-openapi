<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

final readonly class Items implements Keyword
{
    private function __construct(
        private LooseFluentDescriptor $descriptor,
    ) {
    }

    public static function create(JSONSchema $descriptor): self
    {
        return new self($descriptor);
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
        return $this->descriptor;
    }
}
