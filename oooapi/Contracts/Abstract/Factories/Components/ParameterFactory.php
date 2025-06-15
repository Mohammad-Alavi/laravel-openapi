<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;

abstract class ParameterFactory extends Reusable
{
    final protected static function componentNamespace(): string
    {
        return '/parameters';
    }

    abstract public function build(): Parameter;
}
