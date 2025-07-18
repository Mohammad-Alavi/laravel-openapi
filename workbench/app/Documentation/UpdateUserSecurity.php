<?php

namespace Workbench\App\Documentation;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\SecurityFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use Workbench\App\Petstore\Security\SecurityRequirements\TestBearerSecurityRequirementFactory;
use Workbench\App\Petstore\Security\SecurityRequirements\TestMultiSecurityRequirementFactory;

final readonly class UpdateUserSecurity implements SecurityFactory
{
    public function build(): Security
    {
        return Security::create(
            TestBearerSecurityRequirementFactory::create(),
            TestMultiSecurityRequirementFactory::create(),
        );
    }
}
