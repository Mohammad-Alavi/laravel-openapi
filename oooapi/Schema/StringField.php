<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema;

abstract readonly class StringField implements \JsonSerializable
{
    abstract public function value(): string;

    public function jsonSerialize(): string
    {
        return $this->value();
    }
}
