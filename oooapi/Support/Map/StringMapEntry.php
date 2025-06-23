<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Map;

use Webmozart\Assert\Assert;

/**
 * @template TValue
 */
abstract readonly class StringMapEntry
{
    /**
     * @param TValue $value
     */
    final protected function __construct(
        private string|\Stringable $key,
        private mixed $value,
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
