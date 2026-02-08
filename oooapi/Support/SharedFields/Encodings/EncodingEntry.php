<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Encodings;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Encoding\Encoding;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;

/**
 * @implements StringMapEntry<EncodingMap>
 */
final readonly class EncodingEntry implements StringMapEntry
{
    /** @use StringKeyedMapEntry<EncodingMap> */
    use StringKeyedMapEntry;

    public static function create(string $name, Encoding $encoding): self
    {
        return new self($name, $encoding);
    }
}
