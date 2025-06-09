<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Components\Fields\Links;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Map\StringMap;

/**
 * @extends StringMap<Link>
 */
final readonly class Links extends StringMap
{
    public static function create(LinkEntry ...$entry): self
    {
        return parent::put(...$entry);
    }
}
