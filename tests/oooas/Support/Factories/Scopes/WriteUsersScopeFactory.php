<?php

namespace Tests\oooas\Support\Factories\Scopes;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\Scope;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth\ScopeFactory;

final readonly class WriteUsersScopeFactory extends ScopeFactory
{
    public function build(): Scope
    {
        return Scope::create('write:users', 'Write user data');
    }
}
