<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Defs;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;

final readonly class Def
{
    private function __construct(
        private string $name,
        private Descriptor $builder,
    ) {
    }

    public static function create(string $name, Descriptor $builder): self
    {
        return new self($name, $builder);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): Descriptor
    {
        return $this->builder;
    }
}
