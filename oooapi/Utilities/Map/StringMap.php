<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Map;

use Webmozart\Assert\Assert;

/**
 * @template TValue
 */
abstract readonly class StringMap implements \JsonSerializable
{
    /**
     * @param array<int, StringMapEntry<TValue>> $entries
     */
    final private function __construct(
        protected array $entries,
    ) {
        Assert::uniqueValues($this->keys());
    }

    /**
     * @return string[]
     */
    final public function keys(): array
    {
        return array_map(
            static fn (StringMapEntry $stringMapEntry): string => $stringMapEntry->key(),
            $this->entries(),
        );
    }

    /**
     * @return array<int, StringMapEntry<TValue>>
     */
    final public function entries(): array
    {
        return $this->entries;
    }

    final protected static function put(StringMapEntry ...$mapEntry): static
    {
        return new static($mapEntry);
    }

    /** @return array<string, TValue>|null */
    final public function jsonSerialize(): array|null
    {
        if ([] === $this->entries()) {
            return null;
        }

        $entries = [];
        foreach ($this->entries() as $entry) {
            $entries[$entry->key()] = $entry->value();
        }

        return $entries;
    }
}
