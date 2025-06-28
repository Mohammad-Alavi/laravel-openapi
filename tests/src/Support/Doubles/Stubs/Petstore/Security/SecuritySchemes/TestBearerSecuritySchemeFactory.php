<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

class TestBearerSecuritySchemeFactory extends SecuritySchemeFactory
{
    public function component(): SecurityScheme
    {
        return SecurityScheme::http(Http::bearer(), Description::create('Example Security'));
    }
}
