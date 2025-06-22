<?php

namespace Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Objects\SecurityRequirementFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\RequiredSecurity;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeCollection;
use Tests\Doubles\Stubs\Petstore\Security\Scopes\OrderShippingAddressScope;
use Tests\Doubles\Stubs\Petstore\Security\Scopes\OrderShippingStatusScope;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestBearerSecuritySchemeFactory;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestOAuth2PasswordSecuritySchemeFactory;

final readonly class TestMultiSecurityRequirementFactory extends SecurityRequirementFactory
{
    public function object(): SecurityRequirement
    {
        return SecurityRequirement::create(
            RequiredSecurity::create(
                TestBearerSecuritySchemeFactory::create(),
            ),
            RequiredSecurity::create(
                TestOAuth2PasswordSecuritySchemeFactory::create(),
                ScopeCollection::create(
                    OrderShippingAddressScope::create(),
                    OrderShippingStatusScope::create(),
                ),
            ),
        );
    }
}
