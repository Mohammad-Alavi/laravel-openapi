<?php

namespace Tests\Unit\Attributes\Stubs;

use MohammadAlavi\ObjectOrientedOAS\Objects\Schema;
use MohammadAlavi\LaravelOpenApi\Factories\ExtensionFactory;

class ExtensionFactoryStub extends ExtensionFactory
{
    public function build(): array
    {
        return [];
    }

    public function key(): string
    {
        return 'key';
    }

    public function value(): array|string|Schema|null
    {
        return 'value';
    }
}