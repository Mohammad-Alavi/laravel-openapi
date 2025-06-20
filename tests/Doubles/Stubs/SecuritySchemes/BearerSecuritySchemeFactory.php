<?php

namespace Tests\Doubles\Stubs\SecuritySchemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;

class BearerSecuritySchemeFactory extends SecuritySchemeFactory
{
    public static function name(): string
    {
        return 'Bearer';
    }

    public function build(): SecurityScheme
    {
        return SecurityScheme::http(Http::bearer('Example Bearer Security'));
    }
}
