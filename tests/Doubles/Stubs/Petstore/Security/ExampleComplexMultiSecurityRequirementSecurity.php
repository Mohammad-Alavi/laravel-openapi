<?php

namespace Tests\Doubles\Stubs\Petstore\Security;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\SecurityFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements\ExampleMultiSecurityRequirement;
use Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements\ExampleSingleBearerSecurityRequirement;

class ExampleComplexMultiSecurityRequirementSecurity implements SecurityFactory
{
    public function build(): Security
    {
        return Security::create(
            ExampleSingleBearerSecurityRequirement::create(),
            ExampleMultiSecurityRequirement::create(),
        );
    }
}
