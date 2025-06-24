<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;

/**
 * @extends StringMapEntry<Operation>
 */
final readonly class AvailableOperation extends StringMapEntry
{
    public static function create(HttpMethod $method, Operation $operation): self
    {
        return new self($method->value, $operation);
    }
}
