<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;

abstract class SecuritySchemeFactory extends ReusableComponent
{
    final protected static function componentNamespace(): string
    {
        return '/securitySchemes';
    }

    abstract public function component(): SecurityScheme;
}
