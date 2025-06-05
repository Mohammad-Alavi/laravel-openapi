<?php

namespace Tests\Doubles\Stubs\Petstore\Parameters;

use MohammadAlavi\LaravelOpenApi\Collections\ParameterCollection;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\Collections\ParameterCollectionFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\In\In;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Formats\IntegerFormat;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

class ListPetsParameterCollection implements ParameterCollectionFactory
{
    public function build(): ParameterCollection
    {
        return ParameterCollection::create(
            Parameter::schema(
                Name::create('limit'),
                In::query(),
                Schema::integer()
                    ->format(IntegerFormat::INT32),
            )->description(Description::create('How many items to return at one time (max 100)')),
        );
    }
}
