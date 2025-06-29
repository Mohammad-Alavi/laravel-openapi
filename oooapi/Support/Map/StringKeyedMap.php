<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Map;

use Webmozart\Assert\Assert;

/**
 * @template TValue
 */
trait StringKeyedMap
{
    /**
     * @param StringMapEntry[] $entries
     */
    final public function __construct(
        protected readonly array $entries,
    ) {
        Assert::uniqueValues($this->keys());
    }

    /**
     * @return string[]
     */
    final public function keys(): array
    {
        return array_map(
            static function (StringMapEntry $stringMapEntry): string {
                return $stringMapEntry->key();
            },
            $this->entries(),
        );
    }

    /**
     * @return StringMapEntry[]
     */
    final public function entries(): array
    {
        return $this->entries;
    }

    final public static function put(StringMapEntry ...$stringMapEntry): static
    {
        return new static($stringMapEntry);
    }

    /** @return array<string, TValue>|null */
    final public function jsonSerialize(): array|null
    {
        if (blank($this->entries())) {
            return null;
        }

        $entries = [];
        foreach ($this->entries() as $entry) {
            $entries[$entry->key()] = $entry->value();
        }

        return $entries;
    }
}
