<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptor\Descriptor;

final readonly class Property
{
    private function __construct(
        private string $name,
        private Descriptor $descriptor,
    ) {
    }

    public static function create(string $name, Descriptor $descriptor): self
    {
        return new self($name, $descriptor);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function schema(): Descriptor
    {
        return $this->descriptor;
    }
}
