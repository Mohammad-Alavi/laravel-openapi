<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;

/**
 * @extends StringMapEntry<MediaType>
 */
final readonly class MediaTypeEntry extends StringMapEntry
{
    public static function create(string $name, MediaType $mediaType): self
    {
        return new self($name, $mediaType);
    }
}
