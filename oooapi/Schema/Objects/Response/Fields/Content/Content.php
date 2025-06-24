<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Content;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;

/**
 * @implements StringMap<MediaType>
 */
final readonly class Content implements StringMap
{
    /** @use StringKeyedMap<MediaType> */
    use StringKeyedMap;

    public static function create(ContentEntry ...$entry): self
    {
        return self::put(...$entry);
    }
}
