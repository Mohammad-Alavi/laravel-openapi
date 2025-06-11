<?php

namespace Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Enums\ApiKeyLocation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Schemes\ApiKey;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme;

class ExampleApiKeySecurityScheme extends SecuritySchemeFactory
{
    public function build(): SecurityScheme
    {
        return ApiKey::create('ApiKey Security', ApiKeyLocation::COOKIE);
    }
}
