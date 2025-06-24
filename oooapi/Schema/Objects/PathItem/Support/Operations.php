<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

/**
 * @extends StringMap<Operation>
 */
final readonly class Operations extends StringMap
{
    public static function create(AvailableOperation ...$availableOperation): self
    {
        return parent::put(...$availableOperation);
    }
}
