<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Fields\Links;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;

/**
 * @implements StringMap<Link>
 */
final readonly class Links implements StringMap
{
    /** @use StringKeyedMap<Link> */
    use StringKeyedMap;

    public static function create(LinkEntry ...$entry): self
    {
        return self::put(...$entry);
    }
}
