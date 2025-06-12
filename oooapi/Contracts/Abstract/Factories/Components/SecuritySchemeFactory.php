<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components\SecuritySchemeFactory as SecuritySchemeFactoryContract;

abstract class SecuritySchemeFactory extends ReusableComponent implements SecuritySchemeFactoryContract
{
    final protected static function componentNamespace(): string
    {
        return '/securitySchemes';
    }
}
