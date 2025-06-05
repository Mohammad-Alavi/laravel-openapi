<?php

namespace Tests\Doubles\Stubs\Concerns;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableParameterFactory as AbstractReusableParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\In\In;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

class TestReusableParameter extends AbstractReusableParameterFactory
{
    public function build(): Parameter
    {
        return Parameter::schema(Name::create('TestReusableParameter'), In::query(), Schema::create())
            ->description(Description::create('ReusableParameterStub description'))
            ->required();
    }
}
