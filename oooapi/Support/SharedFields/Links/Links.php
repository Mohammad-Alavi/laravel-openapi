<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Links;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Links\LinkEntry;

/**
 * @implements StringMap<OASObject>
 */
final readonly class Links implements StringMap
{
    /** @use StringKeyedMap<OASObject> */
    use StringKeyedMap;

    public static function create(LinkEntry ...$linkEntry): self
    {
        return self::put(...$linkEntry);
    }
}
