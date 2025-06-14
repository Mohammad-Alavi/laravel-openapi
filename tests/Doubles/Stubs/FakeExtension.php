<?php

namespace Tests\Doubles\Stubs;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\ExtensionFactory;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

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
