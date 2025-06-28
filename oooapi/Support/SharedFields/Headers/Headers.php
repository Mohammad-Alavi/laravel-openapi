<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Headers;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Headers\HeaderEntry;

/**
 * @implements StringMap<OASObject>
 */
final readonly class Headers implements StringMap
{
    /** @use StringKeyedMap<OASObject> */
    use StringKeyedMap;

    public static function create(HeaderEntry ...$entry): self
    {
        return self::put(...$entry);
    }
}
