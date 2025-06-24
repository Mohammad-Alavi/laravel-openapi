<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Links;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;

/**
 * @implements StringMap<OASObject>
 */
final readonly class Links implements StringMap
{
    /** @use StringKeyedMap<OASObject> */
    use StringKeyedMap;

    public static function create(LinkEntry ...$entry): self
    {
        return self::put(...$entry);
    }
}
