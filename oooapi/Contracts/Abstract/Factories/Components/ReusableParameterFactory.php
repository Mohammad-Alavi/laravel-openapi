<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components\ParameterFactory;

abstract class ReusableParameterFactory extends ReusableComponent implements ParameterFactory
{
    final protected static function componentNamespace(): string
    {
        return '/parameters';
    }
}
