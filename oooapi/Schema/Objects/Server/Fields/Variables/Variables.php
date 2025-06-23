<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Server\Fields\Variables;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ServerVariable\ServerVariable;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

/**
 * @extends StringMap<ServerVariable>
 */
final readonly class Variables extends StringMap
{
    public static function create(VariableEntry ...$entry): self
    {
        return parent::put(...$entry);
    }
}
