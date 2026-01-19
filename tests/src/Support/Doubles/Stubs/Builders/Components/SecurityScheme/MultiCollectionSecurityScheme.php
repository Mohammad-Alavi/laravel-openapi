<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\SecurityScheme;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;

#[Collection(['test', Collection::DEFAULT])]
class MultiCollectionSecurityScheme extends SecuritySchemeFactory implements ShouldBeReferenced
{
    public function component(): SecurityScheme
    {
        return SecurityScheme::http(Http::basic());
    }
}
