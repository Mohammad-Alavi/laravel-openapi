<?php

namespace Tests\Doubles\Stubs\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Enums\ApiKeyLocation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Schemes\ApiKey;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme;

class BSecuritySchemeFactory extends SecuritySchemeFactory
{
    public function build(): SecurityScheme
    {
        return ApiKey::create('header', ApiKeyLocation::HEADER, 'api_key');
    }
}
