<?php

namespace Workbench\App\Documentation\Parameters;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ParametersFactory;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\PathParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;

class UpdateUserParameters implements ParametersFactory
{
    public function build(): Parameters
    {
        return Parameters::create(
            Parameter::path(
                'id',
                PathParameter::create(
                    Schema::string()
                        ->format(StringFormat::UUID),
                ),
            ),
        );
    }
}
