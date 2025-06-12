<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components\ResponseFactory;

abstract class ReusableResponseFactory extends ReusableComponent implements ResponseFactory
{
    final protected static function componentNamespace(): string
    {
        return '/responses';
    }
}
