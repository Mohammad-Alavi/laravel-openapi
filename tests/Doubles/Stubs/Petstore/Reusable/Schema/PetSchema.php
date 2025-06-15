<?php

namespace Tests\Doubles\Stubs\Petstore\Reusable\Schema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldReuse;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Formats\IntegerFormat;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

class PetSchema extends SchemaFactory implements ShouldReuse
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
