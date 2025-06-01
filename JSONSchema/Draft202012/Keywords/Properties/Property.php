<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;

final readonly class Property
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

    public function schema(): Descriptor
    {
        return $this->builder;
    }
}
