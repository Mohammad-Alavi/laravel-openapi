<?php

namespace Tests\Doubles\Stubs\Attributes;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\ResponsesFactory as ResponsesFactoryAbstract;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses;

class ResponsesFactory extends ResponsesFactoryAbstract
{
    public function build(): Responses
    {
        return Responses::create(Response::ok());
    }
}
