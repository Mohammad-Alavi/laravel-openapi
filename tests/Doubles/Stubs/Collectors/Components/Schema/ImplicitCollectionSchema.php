<?php

namespace Tests\Doubles\Stubs\Collectors\Components\Schema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ReusableSchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

class ImplicitCollectionSchema extends ReusableSchemaFactory
{
    public function build(): JSONSchema
    {
        return Schema::object()
            ->properties(
                Property::create(
                    'id',
                    Schema::integer(),
                ),
            );
    }
}
