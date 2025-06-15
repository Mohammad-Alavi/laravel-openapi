<?php

namespace Tests\Doubles\Stubs\Concerns;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

class TestParameter extends ParameterFactory
{
    public function build(): Parameter
    {
        return Parameter::query(
            Name::create('TestReusableParameter'),
            SchemaSerializedQuery::create(Schema::string()),
        )->description(Description::create('ReusableParameterStub description'))
            ->required();
    }
}
