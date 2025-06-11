<?php

namespace Tests\Doubles\Stubs\Petstore\Parameters;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Collections\ParameterCollectionFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Common\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedQuery;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ParameterCollection;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Formats\IntegerFormat;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

class ListPetsParameterCollection implements ParameterCollectionFactory
{
    public function build(): ParameterCollection
    {
        return ParameterCollection::create(
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
