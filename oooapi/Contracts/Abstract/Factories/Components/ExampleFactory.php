<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;

abstract class ExampleFactory extends ReusableComponent
{
    final protected static function componentNamespace(): string
    {
        return '/examples';
    }

    abstract public function component(): Example;
}
