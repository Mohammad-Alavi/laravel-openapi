<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;

abstract class RequestBodyFactory extends ReusableComponent
{
    final protected static function componentNamespace(): string
    {
        return '/requestBodies';
    }

    abstract public function component(): RequestBody;
}
