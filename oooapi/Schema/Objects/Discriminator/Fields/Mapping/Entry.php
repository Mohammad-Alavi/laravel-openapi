<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Fields\Mapping;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\StringField;

/**
 * @implements StringMapEntry<StringField>
 */
final readonly class Entry implements StringMapEntry
{
    /** @use StringKeyedMapEntry<StringField> */
    use StringKeyedMapEntry;

    public static function create(string $payloadValue, Name|URL $nameOrUrl): self
    {
        return new self($payloadValue, $nameOrUrl);
    }
}
