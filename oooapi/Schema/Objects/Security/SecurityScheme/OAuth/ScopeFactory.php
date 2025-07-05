<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\OAuth;

abstract readonly class ScopeFactory
{
    final private function __construct()
    {
    }

    final public static function create(): static
    {
        return new static();
    }

    abstract public function build(): Scope;
}
