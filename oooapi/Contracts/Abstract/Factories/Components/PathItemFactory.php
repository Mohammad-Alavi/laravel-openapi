<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\PathItem;

abstract class PathItemFactory extends ReusableComponent
{
    final protected static function componentNamespace(): string
    {
        return '/pathItems';
    }

    abstract public function component(): PathItem;
}
