<?php

namespace Tests\Doubles\Stubs\SecuritySchemesFactories;

use MohammadAlavi\LaravelOpenApi\Factories\Component\SecuritySchemeFactory;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\SecurityScheme;

class BearerSecuritySchemeFactory extends SecuritySchemeFactory
{
    public function build(): SecurityScheme
    {
        return SecurityScheme::create('Bearer')
            ->type(SecurityScheme::TYPE_HTTP)
            ->scheme('bearer');
    }
}
