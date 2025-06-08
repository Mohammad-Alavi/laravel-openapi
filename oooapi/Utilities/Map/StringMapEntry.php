<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Map;

use Webmozart\Assert\Assert;

/**
 * @template TValue
 */
abstract readonly class StringMapEntry
{
    /**
     * @param string $key
     * @param TValue $value
     */
    final protected function __construct(
        private string $key,
        private mixed $value,
    ) {
        Assert::notEmpty($this->key);
    }

    /**
     * @return non-empty-array<string, TValue>
     */
    final public function value(): array
    {
        return [$this->key => $this->value];
    }
}
