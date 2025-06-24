<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;

/**
 * @implements StringMapEntry<Operation>
 */
final readonly class AvailableOperation implements StringMapEntry
{
    /** @use StringKeyedMapEntry<Operation> */
    use StringKeyedMapEntry;

    public static function create(HttpMethod $method, Operation $operation): self
    {
        return new self($method->value, $operation);
    }
}
