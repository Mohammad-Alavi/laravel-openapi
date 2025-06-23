<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

/**
 * @extends StringMap<SchemaName>
 * @extends StringMap<SchemaURL>
 */
final readonly class Mapping extends StringMap
{
    public static function create(Entry ...$entry): self
    {
        return parent::put(...$entry);
    }
}
