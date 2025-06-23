<?php

namespace Tests\src\Support\Doubles\Stubs\SecuritySchemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;

class JwtSecuritySchemeFactory extends SecuritySchemeFactory
{
    public static function name(): string
    {
        return 'JWT';
    }

    public function component(): SecurityScheme
    {
        return SecurityScheme::http(Http::bearer('JWT Authentication'), Description::create('JWT'));
    }
}
