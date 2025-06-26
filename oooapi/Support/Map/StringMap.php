<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Map;

/**
 * @template TValue
 */
interface StringMap extends \JsonSerializable
{
    /**
     * @param array<int, StringMapEntry<TValue>> $entries
     */
    public function __construct(array $entries);

    public static function put(StringMapEntry ...$stringMapEntry): static;

    /**
     * @return string[]
     */
    public function keys(): array;

    /**
     * @return array<int, StringMapEntry<TValue>>
     */
    public function entries(): array;

    /** @return array<string, TValue>|null */
    public function jsonSerialize(): array|null;
}
