<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Content;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;

/**
 * @extends StringMapEntry<MediaType>
 */
final readonly class ContentEntry extends StringMapEntry
{
    public static function create(string $name, MediaType $mediaType): self
    {
        return new self($name, $mediaType);
    }
}
