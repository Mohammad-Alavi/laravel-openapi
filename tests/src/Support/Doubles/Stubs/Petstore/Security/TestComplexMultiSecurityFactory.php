<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Security;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\SecurityFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestBearerSecurityRequirementFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestMultiSecurityRequirementFactory;

class TestComplexMultiSecurityFactory implements SecurityFactory
{
    public function build(): Security
    {
        return Security::create(
            TestBearerSecurityRequirementFactory::create(),
            TestMultiSecurityRequirementFactory::create(),
        );
    }
}
