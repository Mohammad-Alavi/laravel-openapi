<?php

namespace Tests\Doubles\Stubs\Petstore\Responses;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use Tests\Doubles\Stubs\Petstore\Responses\Response\ReusableComponentErrorValidationResponse;

class SingleReusableResponses extends ResponsesFactory
{
    public function build(): Responses
    {
        return Responses::create(
            ReusableComponentErrorValidationResponse::create(),
        );
    }
}
