<?php

namespace Tests\src\Support\Doubles\Stubs\Concerns;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Name;

class TestParameter extends ParameterFactory
{
    public function component(): Parameter
    {
        return Parameter::query(
            Name::create('TestReusableParameter'),
            SchemaSerializedQuery::create(Schema::string()),
        )->description(Description::create('ReusableParameterStub description'))
            ->required();
    }
}
