<?php

namespace Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme;

class ExampleHTTPBearerSecurityScheme extends SecuritySchemeFactory
{
    public function build(): SecurityScheme
    {
        return Http::bearer('Example Security');
    }
}
