<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Fields\Links;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringKeyedMapEntry;

/**
 * @implements StringMapEntry<Link>
 */
final readonly class LinkEntry implements StringMapEntry
{
    /** @use StringKeyedMapEntry<Link> */
    use StringKeyedMapEntry;

    public static function create(string $name, Link $link): self
    {
        return new self($name, $link);
    }
}
