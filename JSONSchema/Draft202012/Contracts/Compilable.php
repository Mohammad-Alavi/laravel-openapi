<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts;

/**
 * @template TKey of array-key
 * @template TValue
 */
interface Compilable
{
    /**
     * Get the instance as an array.
     *
     * @return array<TKey, TValue>
     */
    public function compile(): array;
}
