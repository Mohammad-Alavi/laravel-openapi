<?php

namespace Tests\Doubles\Stubs\Petstore\Security;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Objects\SecurityFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestBearerSecurityRequirementFactory;
use Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestMultiSecurityRequirementFactory;

class TestComplexMultiSecurityFactory extends SecurityFactory
{
    public function object(): Security
    {
        return Security::create(
            TestBearerSecurityRequirementFactory::create(),
            TestMultiSecurityRequirementFactory::create(),
        );
    }
}
