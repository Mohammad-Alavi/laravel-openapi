<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema;

abstract readonly class BooleanField implements \JsonSerializable
{
    final protected function __construct(
        private bool $value,
    ) {
    }

    final public static function yes(): static
    {
        return new static(true);
    }

    final public static function no(): static
    {
        return new static(false);
    }

    final public function jsonSerialize(): bool
    {
        return $this->value;
    }
}
