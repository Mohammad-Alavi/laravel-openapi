<?php

namespace Tests\src\Support\Doubles\Stubs\Attributes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;

class TestSecuritySchemeFactory extends SecuritySchemeFactory
{
    public function component(): SecurityScheme
    {
        return SecurityScheme::http(Http::basic());
    }
}
