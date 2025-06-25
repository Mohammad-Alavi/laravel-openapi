<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\Fields\Encoding;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Encoding as EncodingObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

/**
 * @implements StringMap<EncodingObject>
 */
final readonly class Encoding implements StringMap
{
    /** @use StringKeyedMap<EncodingObject> */
    use StringKeyedMap;

    public static function create(EncodingEntry ...$encodingEntry): self
    {
        return self::put(...$encodingEntry);
    }
}
