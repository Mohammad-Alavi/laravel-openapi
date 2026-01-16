<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\MergeableFields;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

/**
 * Collection of HTTP method operations for a PathItem.
 *
 * Operations (get, post, put, etc.) are merged into the parent PathItem
 * at the same level as summary and description, not nested under an
 * "operations" key.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#path-item-object
 *
 * @implements StringMap<Operation>
 */
final readonly class Operations implements StringMap, MergeableFields
{
    /** @use StringKeyedMap<Operation> */
    use StringKeyedMap;

    public static function create(AvailableOperation ...$availableOperation): self
    {
        return self::put(...$availableOperation);
    }
}
