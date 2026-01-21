<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

/**
 * Server Variables map.
 *
 * A map between a variable name and its value used for substitution
 * in the server's URL template.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#server-object
 *
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
