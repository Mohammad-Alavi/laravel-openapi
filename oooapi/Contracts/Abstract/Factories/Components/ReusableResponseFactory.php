<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

abstract class ReusableResponseFactory extends Reusable
{
    final protected static function componentNamespace(): string
    {
        return '/responses';
    }

    abstract public function build(): Response;
}
