<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;

final readonly class Not implements Keyword
{
    private function __construct(
        private JSONSchema $descriptor,
    ) {
    }

    public static function create(JSONSchema $descriptor): self
    {
        return new self($descriptor);
    }

    public static function name(): string
    {
        return 'not';
    }

    public function jsonSerialize(): JSONSchema
    {
        return $this->value();
    }

    public function value(): JSONSchema
    {
        return $this->descriptor;
    }
}
