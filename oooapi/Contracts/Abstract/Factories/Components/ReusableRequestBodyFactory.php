<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components\RequestBodyFactory;

abstract class ReusableRequestBodyFactory extends Reusable implements RequestBodyFactory
{
    final protected static function componentNamespace(): string
    {
        return '/requestBodies';
    }
}
