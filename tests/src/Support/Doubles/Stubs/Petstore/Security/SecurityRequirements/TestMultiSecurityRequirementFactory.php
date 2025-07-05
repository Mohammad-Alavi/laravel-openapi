<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Security\SecurityRequirements;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Composable\SecurityRequirementFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\RequiredSecurity;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeCollection;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\Scopes\OrderShippingAddressScope;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\Scopes\OrderShippingStatusScope;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestBearerSecuritySchemeFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestOAuth2PasswordSecuritySchemeFactory;

final class TestMultiSecurityRequirementFactory extends SecurityRequirementFactory
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
