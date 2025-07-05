<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

/**
 * @implements StringMap<Operation>
 */
final readonly class Operations implements StringMap
{
    /** @use StringKeyedMap<Operation> */
    use StringKeyedMap;

    public static function create(AvailableOperation ...$availableOperation): self
    {
        return self::put(...$availableOperation);
    }
}
