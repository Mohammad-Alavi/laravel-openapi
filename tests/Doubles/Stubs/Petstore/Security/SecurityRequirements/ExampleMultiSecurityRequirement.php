<?php

namespace Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\RequiredSecurity;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\SecurityRequirementFactory;
use Tests\Doubles\Stubs\Petstore\Security\Scopes\OrderShippingAddressScope;
use Tests\Doubles\Stubs\Petstore\Security\Scopes\OrderShippingStatusScope;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\ExampleHTTPBearerSecurityScheme;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\ExampleOAuth2PasswordSecurityScheme;

final readonly class ExampleMultiSecurityRequirement extends SecurityRequirementFactory
{
    public function build(): SecurityRequirement
    {
        return SecurityRequirement::create(
            RequiredSecurity::create(
                ExampleHTTPBearerSecurityScheme::create(),
            ),
            RequiredSecurity::create(
                ExampleOAuth2PasswordSecurityScheme::create(),
                ScopeCollection::create(
                    OrderShippingAddressScope::create(),
                    OrderShippingStatusScope::create(),
                ),
            ),
        );
    }
}
