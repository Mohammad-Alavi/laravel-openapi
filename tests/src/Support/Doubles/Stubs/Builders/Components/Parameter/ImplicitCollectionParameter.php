<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\Parameter;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

class ImplicitCollectionParameter extends ParameterFactory implements ShouldBeReferenced
{
    public function component(): Parameter
    {
        return Parameter::query(
            'limit',
            SchemaSerializedQuery::create(Schema::integer()),
        );
    }
}
