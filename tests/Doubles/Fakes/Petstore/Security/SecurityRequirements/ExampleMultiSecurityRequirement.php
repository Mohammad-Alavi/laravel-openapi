<?php

namespace Tests\Doubles\Fakes\Petstore\Security\SecurityRequirements;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\OAuth\ScopeCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\OAuth\SecurityRequirementFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Requirement;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement;
use Tests\Doubles\Fakes\Petstore\Security\Scopes\OrderShippingAddressScope;
use Tests\Doubles\Fakes\Petstore\Security\Scopes\OrderShippingStatusScope;
use Tests\Doubles\Fakes\Petstore\Security\SecuritySchemes\ExampleHTTPBearerSecurityScheme;
use Tests\Doubles\Fakes\Petstore\Security\SecuritySchemes\ExampleOAuth2PasswordSecurityScheme;

final readonly class ExampleMultiSecurityRequirement extends SecurityRequirementFactory
{
    public function build(): SecurityRequirement
    {
        return SecurityRequirement::create(
            Requirement::create(
                ExampleHTTPBearerSecurityScheme::create(),
            ),
            Requirement::create(
                ExampleOAuth2PasswordSecurityScheme::create(),
                ScopeCollection::create(
                    OrderShippingAddressScope::create(),
                    OrderShippingStatusScope::create(),
                ),
            ),
        );
    }
}
