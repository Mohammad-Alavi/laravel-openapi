<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;

/**
 * @implements StringMapEntry<ServerVariable>
 */
final readonly class VariableEntry implements StringMapEntry
{
    /** @use StringKeyedMapEntry<ServerVariable> */
    use StringKeyedMapEntry;

    public static function create(string $name, ServerVariable $serverVariable): self
    {
        return new self($name, $serverVariable);
    }
}
