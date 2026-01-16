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

    public static function multipartFormData(MediaType $mediaType): self
    {
        return self::create('multipart/form-data', $mediaType);
    }

    public static function octetStream(MediaType $mediaType): self
    {
        return self::create('application/octet-stream', $mediaType);
    }

    public static function html(MediaType $mediaType): self
    {
        return self::create('text/html', $mediaType);
    }

    public static function csv(MediaType $mediaType): self
    {
        return self::create('text/csv', $mediaType);
    }

    public static function gif(MediaType $mediaType): self
    {
        return self::create('image/gif', $mediaType);
    }

    public static function svg(MediaType $mediaType): self
    {
        return self::create('image/svg+xml', $mediaType);
    }

    public static function webp(MediaType $mediaType): self
    {
        return self::create('image/webp', $mediaType);
    }

    public static function zip(MediaType $mediaType): self
    {
        return self::create('application/zip', $mediaType);
    }

    public static function yaml(MediaType $mediaType): self
    {
        return self::create('application/yaml', $mediaType);
    }

    /**
     * Any content type (wildcard).
     */
    public static function any(MediaType $mediaType): self
    {
        return self::create('*/*', $mediaType);
    }
}
