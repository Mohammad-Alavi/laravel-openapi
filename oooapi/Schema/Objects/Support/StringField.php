<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support;

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
