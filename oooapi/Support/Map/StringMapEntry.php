<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Map;

/**
 * @template TValue
 */
interface StringMapEntry
{
    /**
     * @param TValue $value
     */
    public function __construct(string|\Stringable $key, mixed $value);

    public function key(): string;

    /**
     * @return TValue
     */
    public function value();
}
