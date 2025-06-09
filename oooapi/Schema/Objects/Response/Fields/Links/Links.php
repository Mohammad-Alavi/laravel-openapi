<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Links;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Map\StringMap;

/**
 * @extends StringMap<Link>
 * @extends StringMap<Reference>
 */
final readonly class Links extends StringMap
{
    public static function create(LinkEntry ...$entry): self
    {
        return parent::put(...$entry);
    }
}
