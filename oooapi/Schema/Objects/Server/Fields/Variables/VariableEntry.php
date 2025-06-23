<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;

/**
 * @extends StringMapEntry<ServerVariable>
 */
final readonly class VariableEntry extends StringMapEntry
{
    public static function create(string $name, ServerVariable $serverVariable): self
    {
        return new self($name, $serverVariable);
    }
}
