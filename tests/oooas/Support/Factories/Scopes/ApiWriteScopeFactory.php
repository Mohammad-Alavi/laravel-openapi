<?php

namespace Tests\oooas\Support\Factories\Scopes;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Scope;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeFactory;

final readonly class ApiWriteScopeFactory extends ScopeFactory
{
    public function build(): Scope
    {
        return Scope::create('api:write', 'Write API');
    }
}
