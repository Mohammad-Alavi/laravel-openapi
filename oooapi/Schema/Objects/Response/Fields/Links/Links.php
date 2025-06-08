<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Links;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Map\StringMap;

/**
 * @extends StringMap<Link>
 */
final readonly class Links extends StringMap
{
    public static function create(Entry ...$entry): self
    {
        return parent::put(...$entry);
    }
}
