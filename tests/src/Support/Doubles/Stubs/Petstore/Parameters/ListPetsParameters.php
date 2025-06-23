<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Parameters;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\ParametersFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Formats\IntegerFormat;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\Collections\Parameters;

class ListPetsParameters implements ParametersFactory
{
    public function build(): Parameters
    {
        return Parameters::create(
            Parameter::query(
                Name::create('limit'),
                SchemaSerializedQuery::create(
                    Schema::integer()
                        ->format(IntegerFormat::INT32),
                ),
            )->description(Description::create('How many items to return at one time (max 100)')),
        );
    }
}
