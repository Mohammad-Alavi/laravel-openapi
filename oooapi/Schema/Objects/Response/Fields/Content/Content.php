<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Content;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Map\StringMap;

/**
 * @extends StringMap<MediaType>
 */
final readonly class Content extends StringMap
{
    public static function create(ContentEntry ...$entry): self
    {
        return parent::put(...$entry);
    }
}
