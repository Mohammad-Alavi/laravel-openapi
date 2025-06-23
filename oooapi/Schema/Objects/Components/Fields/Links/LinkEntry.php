<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Fields\Links;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;

/**
 * @extends StringMapEntry<Link>
 */
final readonly class LinkEntry extends StringMapEntry
{
    public static function create(string $name, Link $link): self
    {
        return new self($name, $link);
    }
}
