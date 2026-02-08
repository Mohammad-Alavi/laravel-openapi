<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Encodings;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Encoding\Encoding;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

/**
 * @implements StringMap<Encoding>
 */
final readonly class EncodingMap implements StringMap
{
    /** @use StringKeyedMap<Encoding> */
    use StringKeyedMap;

    public static function create(EncodingEntry ...$encodingEntry): self
    {
        return self::put(...$encodingEntry);
    }
}
