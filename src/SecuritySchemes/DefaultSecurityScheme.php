<?php

namespace MohammadAlavi\LaravelOpenApi\SecuritySchemes;

use MohammadAlavi\ObjectOrientedOAS\Objects\SecurityScheme;
use MohammadAlavi\LaravelOpenApi\Factories\Component\SecuritySchemeFactory;

class DefaultSecurityScheme extends SecuritySchemeFactory
{
    public const NAME = 'DefaultSecurityScheme';

    public function build(): SecurityScheme
    {
        return SecurityScheme::create('DefaultSecurityScheme')
            ->name('DefaultSecurityScheme');
    }
}
