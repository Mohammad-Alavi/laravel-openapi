<?php

namespace Tests\Doubles\Stubs\Attributes;

use MohammadAlavi\LaravelOpenApi\Collections\ParameterCollection;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\Collections\ParameterCollectionFactory as ParametersFactoryInterface;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\CommonFields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Tests\Doubles\Stubs\Concerns\TestReusableParameter;

class ParameterFactory implements ParametersFactoryInterface
{
    public function build(): ParameterCollection
    {
        return ParameterCollection::create(
            Parameter::header(Name::create('param_a'))->schema(Schema::string()),
            Parameter::path(Name::create('param_b'))->schema(Schema::string()),
            TestReusableParameter::create(),
            Parameter::cookie(Name::create('param_c'))->schema(Schema::string()),
        );
    }
}
