<?php

namespace Tests\Doubles\Stubs\Petstore\Security;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\SecurityFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestBearerSecurityRequirementFactory;
use Tests\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestMultiSecurityRequirementFactory;

class TestComplexMultiSecurityFactory implements SecurityFactory
{
    public function object(): Security
    {
        return Security::create(
            TestBearerSecurityRequirementFactory::create(),
            TestMultiSecurityRequirementFactory::create(),
        );
    }
}
