<?php

namespace Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\RequiredSecurity;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\SecurityRequirementFactory;
use Tests\Doubles\Stubs\Petstore\Security\SecuritySchemes\ExampleApiKeySecurityScheme;

final readonly class ExampleSingleApiKeySecurityRequirement extends SecurityRequirementFactory
{
    public function build(): SecurityRequirement
    {
        return SecurityRequirement::create(
            RequiredSecurity::create(
                ExampleApiKeySecurityScheme::create(),
            ),
        );
    }
}
