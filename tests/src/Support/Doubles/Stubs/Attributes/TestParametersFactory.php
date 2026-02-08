<?php

namespace Tests\src\Support\Doubles\Stubs\Attributes;

use MohammadAlavi\LaravelOpenApi\Contracts\Factories\ParametersFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\CookieParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\HeaderParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization\PathParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;
use Tests\src\Support\Doubles\Stubs\TestParameter;

class TestParametersFactory implements ParametersFactory
{
    public function build(): Parameters
    {
        return Parameters::create(
            Parameter::header(
                'param_a',
                HeaderParameter::create(Schema::string()),
            ),
            Parameter::path(
                'param_b',
                PathParameter::create(Schema::string()),
            ),
            TestParameter::create(),
            Parameter::cookie(
                'param_c',
                CookieParameter::create(Schema::string()),
            ),
        );
    }
}
