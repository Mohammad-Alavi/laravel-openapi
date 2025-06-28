<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content;

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

    public static function json(MediaType $mediaType): self
    {
        return self::create('application/json', $mediaType);
    }

    public static function create(string $name, MediaType $mediaType): self
    {
        return new self($name, $mediaType);
    }

    public static function pdf(MediaType $mediaType): self
    {
        return self::create('application/pdf', $mediaType);
    }

    public static function jpeg(MediaType $mediaType): self
    {
        return self::create('image/jpeg', $mediaType);
    }

    public static function png(MediaType $mediaType): self
    {
        return self::create('image/png', $mediaType);
    }

    public static function calendar(MediaType $mediaType): self
    {
        return self::create('text/calendar', $mediaType);
    }

    public static function plainText(MediaType $mediaType): self
    {
        return self::create('text/plain', $mediaType);
    }

    public static function xml(MediaType $mediaType): self
    {
        return self::create('text/xml', $mediaType);
    }

    public static function formUrlEncoded(MediaType $mediaType): self
    {
        return self::create('application/x-www-form-urlencoded', $mediaType);
    }
}
