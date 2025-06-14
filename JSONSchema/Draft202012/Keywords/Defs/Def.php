<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

final readonly class Def
{
    private function __construct(
        private string $name,
        private JSONSchema $descriptor,
    ) {
    }

    public static function create(string $name, JSONSchema $descriptor): self
    {
        return new self($name, $descriptor);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): JSONSchema
    {
        return $this->descriptor;
    }
}
