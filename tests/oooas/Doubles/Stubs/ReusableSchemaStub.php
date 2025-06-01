<?php

namespace Tests\oooas\Doubles\Stubs;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableSchemaFactory;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Schema;

class ReusableSchemaStub extends ReusableSchemaFactory
{
    public function build(): JSONSchema
    {
        return Schema::object()
            ->properties(
                Property::create('id', Schema::integer()),
                Property::create('name', Schema::string()),
            );
    }
}
