<?php

namespace Workbench\App\Petstore\Security;

use MohammadAlavi\LaravelOpenApi\Contracts\Factories\SecurityFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;

class TestEmptySecurityFactory implements SecurityFactory
{
    public function build(): Security
    {
        return Security::create();
    }
}
