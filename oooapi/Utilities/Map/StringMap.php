<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Map;

/**
 * @template TValue
 */
abstract readonly class StringMap implements \JsonSerializable
{
    /**
     * @param StringMapEntry<TValue>[] $entries
     */
    final private function __construct(
        protected array $entries,
    ) {
    }

    final protected static function put(StringMapEntry ...$mapEntry): static
    {
        return new static($mapEntry);
    }

    /** @return array<string, TValue>|null */
    final public function jsonSerialize(): array|null
    {
        if ([] === $this->entries) {
            return null;
        }

        return array_reduce(
            $this->entries,
            static fn (array $carry, StringMapEntry $entry): array => array_merge($carry, $entry->value()),
            [],
        );
    }
}
