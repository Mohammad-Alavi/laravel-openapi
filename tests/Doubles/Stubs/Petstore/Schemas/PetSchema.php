<?php

namespace Tests\Doubles\Stubs\Petstore\Schemas;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableSchemaFactory;
use MohammadAlavi\ObjectOrientedJSONSchema\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\Interface\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Formats\IntegerFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Schema;

class PetSchema extends ReusableSchemaFactory
{
    public function build(): JSONSchema
    {
        return Schema::object()
            ->required('id', 'name')
            ->properties(
                Property::create(
                    'id',
                    Schema::integer()
                        ->format(IntegerFormat::INT64),
                ),
                Property::create(
                    'name',
                    Schema::string(),
                ),
                Property::create(
                    'tag',
                    Schema::string(),
                ),
            );
    }
}
