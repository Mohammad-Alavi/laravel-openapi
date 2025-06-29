<?php

namespace Tests\src\Support\Doubles\Stubs\Attributes;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ParametersFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedCookie;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedHeader;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedPath;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;
use Tests\src\Support\Doubles\Stubs\Concerns\TestParameter;

class TestParameterFactory implements ParametersFactory
{
    public function build(): Parameters
    {
        return Parameters::create(
            Parameter::header(
                'param_a',
                SchemaSerializedHeader::create(Schema::string()),
            ),
            Parameter::path(
                'param_b',
                SchemaSerializedPath::create(Schema::string()),
            ),
            TestParameter::create(),
            Parameter::cookie(
                'param_c',
                SchemaSerializedCookie::create(Schema::string()),
            ),
        );
    }
}
