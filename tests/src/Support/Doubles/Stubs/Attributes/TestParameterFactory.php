<?php

namespace Tests\src\Support\Doubles\Stubs\Attributes;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ParametersFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedCookie;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedHeader;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedPath;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Parameters;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Tests\src\Support\Doubles\Stubs\Concerns\TestParameter;

class TestParameterFactory implements ParametersFactory
{
    public function build(): Parameters
    {
        return Parameters::create(
            Parameter::header(
                Name::create('param_a'),
                SchemaSerializedHeader::create(Schema::string()),
            ),
            Parameter::path(
                Name::create('param_b'),
                SchemaSerializedPath::create(Schema::string()),
            ),
            TestParameter::create(),
            Parameter::cookie(
                Name::create('param_c'),
                SchemaSerializedCookie::create(Schema::string()),
            ),
        );
    }
}
