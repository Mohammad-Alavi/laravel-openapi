<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;

abstract class SecuritySchemeFactory extends ReusableComponent implements ShouldBeReferenced
{
    final protected static function componentNamespace(): string
    {
        return '/securitySchemes';
    }

    abstract public function component(): SecurityScheme;
}
