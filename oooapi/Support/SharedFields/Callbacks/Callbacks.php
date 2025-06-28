<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Callbacks;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Callbacks\CallbackEntry;

/**
 * @implements StringMap<OASObject>
 */
final readonly class Callbacks implements StringMap
{
    /** @use StringKeyedMap<OASObject> */
    use StringKeyedMap;

    public static function create(CallbackEntry ...$callbackEntry): self
    {
        return self::put(...$callbackEntry);
    }
}
