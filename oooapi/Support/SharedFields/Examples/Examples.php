<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Examples;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

/**
 * @implements StringMap<OASObject>
 */
final readonly class Examples implements StringMap
{
    /** @use StringKeyedMap<OASObject> */
    use StringKeyedMap;

    public static function create(ExampleEntry ...$exampleEntry): self
    {
        return self::put(...$exampleEntry);
    }
}
