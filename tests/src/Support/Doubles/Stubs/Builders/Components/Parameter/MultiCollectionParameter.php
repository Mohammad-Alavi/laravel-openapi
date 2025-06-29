<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\Parameter;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedCookie;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

#[Collection(['test', Generator::COLLECTION_DEFAULT])]
class MultiCollectionParameter extends ParameterFactory implements ShouldBeReferenced
{
    public function component(): Parameter
    {
        return Parameter::cookie(
            'test',
            SchemaSerializedCookie::create(Schema::string()),
        );
    }
}
