<?php

namespace Tests\Doubles\Stubs\Concerns;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableParameterFactory as AbstractReusableParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

class TestReusableParameter extends AbstractReusableParameterFactory
{
    public function build(): Parameter
    {
        return Parameter::query(
            Name::create('TestReusableParameter'),
            SchemaSerializedQuery::create(Schema::create()),
        )->description(Description::create('ReusableParameterStub description'))
            ->required();
    }
}
