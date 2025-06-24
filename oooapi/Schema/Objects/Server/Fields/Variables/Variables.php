<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;

/**
 * @implements StringMap<ServerVariable>
 */
final readonly class Variables implements StringMap
{
    /** @use StringKeyedMap<ServerVariable> */
    use StringKeyedMap;

    public static function create(VariableEntry ...$entry): self
    {
        return self::put(...$entry);
    }
}
