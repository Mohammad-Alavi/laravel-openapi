<?php

namespace Tests\Doubles\Stubs\SecuritySchemes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Enums\ApiKeyLocation;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Schemes\ApiKey;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme;

class ApiKeySecuritySchemeFactory extends SecuritySchemeFactory
{
    public function build(): SecurityScheme
    {
        return ApiKey::create('header', ApiKeyLocation::QUERY, 'Api Key Security');
    }

    public static function key(): string
    {
        return 'ApiKey';
    }
}
