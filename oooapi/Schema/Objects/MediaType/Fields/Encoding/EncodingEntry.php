<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\Fields\Encoding;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Encoding as EncodingObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;

/**
 * @implements StringMapEntry<EncodingObject>
 */
final readonly class EncodingEntry implements StringMapEntry
{
    /** @use StringKeyedMapEntry<EncodingObject> */
    use StringKeyedMapEntry;

    public static function create(string $name, EncodingObject $encoding): self
    {
        return new self($name, $encoding);
    }
}
