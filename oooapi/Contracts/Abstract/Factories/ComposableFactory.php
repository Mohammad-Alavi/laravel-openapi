<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories;

/**
 * @template T
 */
abstract class ComposableFactory
{
    final private function __construct()
    {
    }

    /**
     * Creates an instance of the factory and returns the object it creates.
     *
     * @return T
     */
    final public static function create(): mixed
    {
        return (new static())->object();
    }

    /**
     * Returns the object that this factory creates.
     *
     * @return T
     */
    abstract public function object(): mixed;
}
