<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\Parameter;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedPath;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

#[Collection('test')]
class ExplicitCollectionParameter extends ParameterFactory implements ShouldBeReferenced
{
    public function component(): Parameter
    {
        return Parameter::path(
            'user_id',
            SchemaSerializedPath::create(Schema::string()),
        );
    }
}
