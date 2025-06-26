<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback\Callback;

abstract class CallbackFactory extends ReusableComponent
{
    final protected static function componentNamespace(): string
    {
        return '/callbacks';
    }

    abstract public function component(): Callback;
}
