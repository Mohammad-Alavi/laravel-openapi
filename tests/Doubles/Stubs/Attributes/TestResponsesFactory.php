<?php

namespace Tests\Doubles\Stubs\Attributes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ResponsesFactory as ResponsesFactoryAbstract;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;

class TestResponsesFactory extends ResponsesFactoryAbstract
{
    public function build(): Responses
    {
        return Responses::create(
            ResponseEntry::create(
                HTTPStatusCode::ok(),
                Response::create(
                    Description::create('OK'),
                ),
            ),
        );
    }
}
