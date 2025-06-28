<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

/**
 * @implements StringMap<StringField>
 */
final readonly class Mapping implements StringMap
{
    /** @use StringKeyedMap<StringField> */
    use StringKeyedMap;

    public static function create(Entry ...$entry): self
    {
        return self::put(...$entry);
    }
}
