<?php

namespace Tests\Doubles\Stubs;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\ExtensionFactory;
use MohammadAlavi\ObjectOrientedJSONSchema\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Schema;

class FakeExtension extends ExtensionFactory
{
    public function key(): string
    {
        return 'x-uuid';
    }

    public function value(): JSONSchema
    {
        return Schema::string()->format(StringFormat::UUID);
    }
}
