<?php

namespace Workbench\App\Petstore\Security;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\SecurityFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use Workbench\App\Petstore\Security\SecurityRequirements\TestApiKeySecurityRequirementFactory;
use Workbench\App\Petstore\Security\SecurityRequirements\TestBearerSecurityRequirementFactory;

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
