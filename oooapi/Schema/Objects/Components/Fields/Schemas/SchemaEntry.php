<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Fields\Schemas;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Map\StringMapEntry;

/**
 * @extends StringMapEntry<JSONSchema>
 */
final readonly class SchemaEntry extends StringMapEntry
{
    public static function create(string $name, JSONSchema $jsonSchema): self
    {
        return new self($name, $jsonSchema);
    }
}
