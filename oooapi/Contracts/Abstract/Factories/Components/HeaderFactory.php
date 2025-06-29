<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;

abstract class HeaderFactory extends ReusableComponent
{
    final protected static function componentNamespace(): string
    {
        return '/headers';
    }

    abstract public function component(): Header;
}
