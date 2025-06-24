<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;

/**
 * @implements StringMapEntry<MediaType>
 */
final readonly class MediaTypeEntry implements StringMapEntry
{
    /** @use StringKeyedMapEntry<MediaType> */
    use StringKeyedMapEntry;

    public static function create(string $name, MediaType $mediaType): self
    {
        return new self($name, $mediaType);
    }
}
