<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping;

use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Map\StringMapEntry;

/**
 * @extends StringMapEntry<SchemaName>
 * @extends StringMapEntry<SchemaURL>
 */
final readonly class Entry extends StringMapEntry
{
    public static function create(string $payloadValue, SchemaName|SchemaURL $nameOrUrl): self
    {
        return new self($payloadValue, $nameOrUrl);
    }
}
