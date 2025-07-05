<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Map;

/**
 * @template TValue
 */
interface StringMap extends \JsonSerializable
{
    /**
     * @param StringMapEntry $entries
     */
    public function __construct(array $entries);

    public static function put(StringMapEntry ...$stringMapEntry): static;

    /**
     * @return string[]
     */
    public function keys(): array;

    /**
     * @return StringMapEntry
     */
    public function entries(): array;

    /** @return array<string, TValue>|null */
    public function jsonSerialize(): array|null;
}
