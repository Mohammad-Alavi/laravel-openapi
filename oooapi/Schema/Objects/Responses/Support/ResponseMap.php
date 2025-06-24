<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;

/**
 * @implements StringMap<OASObject>
 */
final readonly class ResponseMap implements StringMap
{
    /** @use StringKeyedMap<OASObject> */
    use StringKeyedMap;

    public static function create(ResponseEntry ...$entry): self
    {
        return self::put(...$entry);
    }
}
