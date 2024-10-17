<?php

namespace Tests\Doubles\Stubs\SecuritySchemes;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme;

class JwtSecuritySchemeFactory extends SecuritySchemeFactory
{
    public static function key(): string
    {
        return 'JWT';
    }

    public function build(): SecurityScheme
    {
        return Http::bearer('JWT Authentication', 'JWT');
    }
}
