<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\SecurityScheme;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;

class ImplicitCollectionSecurityScheme extends SecuritySchemeFactory implements ShouldBeReferenced
{
    public function component(): SecurityScheme
    {
        return SecurityScheme::http(Http::basic());
    }
}
