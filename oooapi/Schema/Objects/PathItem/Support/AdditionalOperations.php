<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

/**
 * Collection of additional (custom) HTTP method operations for a PathItem.
 *
 * Used for HTTP methods beyond the standard ones (get, post, put, etc.)
 * that may be defined by specific APIs or protocols.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#path-item-object
 *
 * @implements StringMap<Operation>
 */
final readonly class AdditionalOperations implements StringMap
{
    /** @use StringKeyedMap<Operation> */
    use StringKeyedMap;

    public static function create(AdditionalOperation ...$additionalOperation): self
    {
        return self::put(...$additionalOperation);
    }
}
