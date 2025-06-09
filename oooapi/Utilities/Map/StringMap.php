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

    final protected static function put(StringMapEntry ...$mapEntry): static
    {
        return new static($mapEntry);
    }

    /**
     * @return string[]
     */
    final public function keys(): array
    {
        return array_map(
            static fn (StringMapEntry $entry): string => $entry->key(),
            $this->entries,
        );
    }

    /**
     * @return array<int, StringMapEntry<TValue>>
     */
    final public function entries(): array
    {
        return $this->entries;
    }

    /** @return array<string, TValue>|null */
    final public function jsonSerialize(): array|null
    {
        if ([] === $this->entries) {
            return null;
        }

        return array_reduce(
            $this->entries,
            static fn (array $carry, StringMapEntry $entry): array => array_merge($carry, $entry->getSet()),
            [],
        );
    }
}
