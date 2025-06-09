<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Map;

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

    /**
     * @return non-empty-array<string, TValue>
     */
    final public function getSet(): array
    {
        return [$this->key() => $this->value];
    }
}
