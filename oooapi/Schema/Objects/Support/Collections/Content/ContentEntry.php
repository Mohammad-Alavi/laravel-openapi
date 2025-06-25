<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Content;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;

/**
 * @implements StringMapEntry<MediaType>
 */
final readonly class ContentEntry implements StringMapEntry
{
    /** @use StringKeyedMapEntry<MediaType> */
    use StringKeyedMapEntry;

    public static function create(string $name, MediaType $mediaType): self
    {
        return new self($name, $mediaType);
    }
}
