<?php

namespace Tests\Doubles\Stubs\Petstore\Responses;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses;
use Tests\Doubles\Stubs\Petstore\Responses\Response\ReusableComponentErrorValidationResponse;

class MixedMultiResponses extends ResponsesFactory
{
    public function build(): Responses
    {
        return Responses::create(
            ReusableComponentErrorValidationResponse::create(),
            Response::forbidden(),
        );
    }
}
