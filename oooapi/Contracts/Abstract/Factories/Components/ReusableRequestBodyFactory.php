<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;

abstract class ReusableRequestBodyFactory extends Reusable
{
    final protected static function componentNamespace(): string
    {
        return '/requestBodies';
    }

    abstract public function build(): RequestBody;
}
