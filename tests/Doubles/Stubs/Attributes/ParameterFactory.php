<?php

namespace Tests\Doubles\Stubs\Attributes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Collections\ParameterCollectionFactory as ParametersFactoryInterface;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedCookie;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedHeader;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedPath;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ParameterCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use Tests\Doubles\Stubs\Concerns\TestReusableParameter;

class ParameterFactory implements ParametersFactoryInterface
{
    public function build(): ParameterCollection
    {
        return ParameterCollection::create(
            Parameter::header(
                Name::create('param_a'),
                SchemaSerializedHeader::create(Schema::string()),
            ),
            Parameter::path(
                Name::create('param_b'),
                SchemaSerializedPath::create(Schema::string()),
            ),
            TestReusableParameter::create(),
            Parameter::cookie(
                Name::create('param_c'),
                SchemaSerializedCookie::create(Schema::string()),
            ),
        );
    }
}
