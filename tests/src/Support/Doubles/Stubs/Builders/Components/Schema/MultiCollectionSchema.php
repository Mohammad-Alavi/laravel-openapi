<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\Schema;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

#[Collection(['test', Generator::COLLECTION_DEFAULT])]
class MultiCollectionSchema extends SchemaFactory implements ShouldBeReferenced
{
    public function component(): JSONSchema
    {
        return Schema::object()
            ->properties(
                Property::create('id', Schema::integer()),
            );
    }
}
