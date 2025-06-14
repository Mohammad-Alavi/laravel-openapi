<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme;

abstract class SecuritySchemeFactory extends Reusable
{
    final protected static function componentNamespace(): string
    {
        return '/securitySchemes';
    }

    abstract public function build(): SecurityScheme;
}
