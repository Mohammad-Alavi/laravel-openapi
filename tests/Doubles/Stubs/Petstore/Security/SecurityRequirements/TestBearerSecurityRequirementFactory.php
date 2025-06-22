<?php

namespace Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Objects\SecurityRequirementFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\RequiredSecurity;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\TestHTTPBearerSecuritySchemeFactory;

final readonly class TestBearerSecurityRequirementFactory extends SecurityRequirementFactory
{
    public function object(): SecurityRequirement
    {
        return SecurityRequirement::create(
            RequiredSecurity::create(
                TestHTTPBearerSecuritySchemeFactory::create(),
            ),
        );
    }
}
