<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;

abstract class LinkFactory extends ReusableComponent
{
    final protected static function componentNamespace(): string
    {
        return '/links';
    }

    abstract public function component(): Link;
}
