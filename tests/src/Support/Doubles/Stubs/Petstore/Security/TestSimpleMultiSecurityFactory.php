<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Security;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Objects\SecurityFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestApiKeySecurityRequirementFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestBearerSecurityRequirementFactory;

class TestSimpleMultiSecurityFactory extends SecurityFactory
{
    public function object(): Security
    {
        return Security::create(
            TestBearerSecurityRequirementFactory::create(),
            TestApiKeySecurityRequirementFactory::create(),
        );
    }
}
