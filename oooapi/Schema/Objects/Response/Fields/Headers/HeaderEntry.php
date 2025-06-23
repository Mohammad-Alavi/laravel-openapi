<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Headers;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMapEntry;

/**
 * @extends StringMapEntry<Header>
 * @extends StringMapEntry<Reference>
 */
final readonly class HeaderEntry extends StringMapEntry
{
    public static function create(string $name, Header|Reference $header): self
    {
        return new self($name, $header);
    }
}
