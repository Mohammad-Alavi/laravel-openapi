<?php

namespace Tests\Doubles\Stubs\SecuritySchemes;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme;

class BearerSecuritySchemeFactory extends SecuritySchemeFactory
{
    public function build(): SecurityScheme
    {
        return Http::bearer('Example Bearer Security');
    }

    public static function key(): string
    {
        return 'Bearer';
    }
}
