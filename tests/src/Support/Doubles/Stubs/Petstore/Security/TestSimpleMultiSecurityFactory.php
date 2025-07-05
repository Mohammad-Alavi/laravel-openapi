<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Security;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\SecurityFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestApiKeySecurityRequirementFactory;
use Tests\src\Support\Doubles\Stubs\Petstore\Security\SecurityRequirements\TestBearerSecurityRequirementFactory;

class TestSimpleMultiSecurityFactory implements SecurityFactory
{
    public function build(): Security
    {
        return Security::create(
            TestBearerSecurityRequirementFactory::create(),
            TestApiKeySecurityRequirementFactory::create(),
        );
    }
}
