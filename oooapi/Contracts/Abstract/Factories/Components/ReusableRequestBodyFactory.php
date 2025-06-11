<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components\RequestBodyFactory;

abstract class ReusableRequestBodyFactory extends ReusableComponent implements RequestBodyFactory
{
    final protected static function componentPath(): string
    {
        return '/requestBodies';
    }
}
