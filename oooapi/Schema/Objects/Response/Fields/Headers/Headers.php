<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Headers;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Map\StringMap;

/**
 * @extends StringMap<Header>
 * @extends StringMap<Reference>
 */
final readonly class Headers extends StringMap
{
    public static function create(HeaderEntry ...$entry): self
    {
        return parent::put(...$entry);
    }
}
