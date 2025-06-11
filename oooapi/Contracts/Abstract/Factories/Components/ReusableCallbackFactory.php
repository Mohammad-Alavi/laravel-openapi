<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components\CallbackFactory;

abstract class ReusableCallbackFactory extends ReusableComponent implements CallbackFactory
{
    final protected static function componentPath(): string
    {
        return '/callbacks';
    }
}
