<?php

namespace Tests\src\Support\Doubles\Stubs\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\ApiKey;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

class BSecuritySchemeFactory extends SecuritySchemeFactory
{
    public function component(): SecurityScheme
    {
        return SecurityScheme::apiKey(ApiKey::header('header'), Description::create('api_key'));
    }
}
