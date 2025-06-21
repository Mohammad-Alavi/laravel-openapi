<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories;

abstract readonly class ObjectFactory
{
    final private function __construct()
    {
    }

    final public static function create(): mixed
    {
        return (new static())->object();
    }

    abstract public function object(): mixed;
}
