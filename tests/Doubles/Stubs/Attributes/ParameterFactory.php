<?php

namespace Tests\Doubles\Stubs\Attributes;

use MohammadAlavi\LaravelOpenApi\Collections\ParameterCollection;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\Collections\ParameterCollectionFactory as ParametersFactoryInterface;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\In\In;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use Tests\Doubles\Stubs\Concerns\TestReusableParameter;

class ParameterFactory implements ParametersFactoryInterface
{
    public function build(): ParameterCollection
    {
        return ParameterCollection::create(
            Parameter::schema(Name::create('param_a'), In::header(), Schema::string()),
            Parameter::schema(Name::create('param_b'), In::path(), Schema::string()),
            TestReusableParameter::create(),
            Parameter::schema(Name::create('param_c'), In::cookie(), Schema::string()),
        );
    }
}
