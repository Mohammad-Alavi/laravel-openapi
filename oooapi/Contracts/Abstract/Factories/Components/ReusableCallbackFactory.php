<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;

abstract class ReusableCallbackFactory extends Reusable
{
    final protected static function componentNamespace(): string
    {
        return '/callbacks';
    }

    abstract public function build(): Callback;
}
