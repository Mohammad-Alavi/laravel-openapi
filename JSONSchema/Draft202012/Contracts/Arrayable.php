<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts;

/**
 * @template TKey of array-key
 * @template TValue
 */
interface Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array<TKey, TValue>
     */
    public function toArray(): array;
}
