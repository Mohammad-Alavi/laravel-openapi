<?php

namespace Tests\Doubles\Stubs\Petstore\Security;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\SecurityFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements\ExampleNoSecurityRequirement;

class ExampleNoSecurityRequirementSecurity implements SecurityFactory
{
    public function object(): Security
    {
        return Security::create(
            ExampleNoSecurityRequirement::create(),
        );
    }
}
