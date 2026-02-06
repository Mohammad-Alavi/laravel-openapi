<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;

abstract class MediaTypeFactory extends ReusableComponent
{
    final protected static function componentNamespace(): string
    {
        return '/mediaTypes';
    }

    abstract public function component(): MediaType;
}
