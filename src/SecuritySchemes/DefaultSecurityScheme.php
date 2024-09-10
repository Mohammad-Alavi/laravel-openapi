<?php

namespace MohammadAlavi\LaravelOpenApi\SecuritySchemes;

use MohammadAlavi\LaravelOpenApi\Factories\Component\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOAS\Objects\SecurityScheme;

class DefaultSecurityScheme extends SecuritySchemeFactory
{
    public const NAME = 'DefaultSecurityScheme';

    public function build(): SecurityScheme
    {
        return SecurityScheme::create('DefaultSecurityScheme')
            ->name('DefaultSecurityScheme');
    }
}
