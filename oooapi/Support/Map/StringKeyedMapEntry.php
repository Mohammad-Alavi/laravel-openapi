<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Map;

use Webmozart\Assert\Assert;

/**
 * @template TValue
 */
trait StringKeyedMapEntry
{
    /**
     * @param TValue $value
     */
    final public function __construct(
        private readonly string|\Stringable $key,
        private readonly mixed $value,
    ) {
        Assert::notEmpty($this->key());
    }

    final public function key(): string
    {
        return (string) $this->key;
    }

    /**
     * @return TValue
     */
    final public function value(): mixed
    {
        return $this->value;
    }
}
