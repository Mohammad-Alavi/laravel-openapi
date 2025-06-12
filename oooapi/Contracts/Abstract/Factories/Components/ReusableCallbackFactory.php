<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components\CallbackFactory;

abstract class ReusableCallbackFactory extends Reusable implements CallbackFactory
{
    final protected static function componentNamespace(): string
    {
        return '/callbacks';
    }
}
