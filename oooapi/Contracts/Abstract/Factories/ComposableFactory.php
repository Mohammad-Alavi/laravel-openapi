<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories;

abstract class ComposableFactory
{
    public static function create(): mixed
    {
        return (new static())->object();
    }

    abstract public function object(): mixed;
}
