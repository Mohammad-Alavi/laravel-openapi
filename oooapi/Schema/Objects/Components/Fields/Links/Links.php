<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Fields\Links;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMap;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

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
