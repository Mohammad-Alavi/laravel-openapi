<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;

abstract class ParameterFactory extends ReusableComponent
{
    final protected static function componentNamespace(): string
    {
        return '/parameters';
    }

    abstract public function component(): Parameter;
}
