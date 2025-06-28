<?php

namespace Tests\src\Support\Doubles\Stubs\SecuritySchemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\ApiKey;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

class ApiKeySecuritySchemeFactory extends SecuritySchemeFactory
{
    public static function name(): string
    {
        return 'ApiKey';
    }

    public function component(): SecurityScheme
    {
        return SecurityScheme::apiKey(ApiKey::query('header'), Description::create('Api Key Security'));
    }
}
