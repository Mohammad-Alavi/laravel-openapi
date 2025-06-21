<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

abstract class ResponseFactory extends ReusableComponent
{
    final protected static function componentNamespace(): string
    {
        return '/responses';
    }

    abstract public function component(): Response;
}
