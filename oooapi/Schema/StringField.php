<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema;

abstract readonly class StringField implements \JsonSerializable, \Stringable
{
    public function __toString(): string
    {
        return $this->value();
    }

    abstract public function value(): string;

    public function jsonSerialize(): string
    {
        return $this->value();
    }
}
