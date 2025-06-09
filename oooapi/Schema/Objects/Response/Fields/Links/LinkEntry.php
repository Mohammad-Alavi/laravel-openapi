<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Links;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Map\StringMapEntry;

/**
 * @extends StringMapEntry<Link>
 * @extends StringMapEntry<Reference>
 */
final readonly class LinkEntry extends StringMapEntry
{
    public static function create(string $name, Link|Reference $link): self
    {
        return new self($name, $link);
    }
}
