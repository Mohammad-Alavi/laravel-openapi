<?php

namespace Tests\Doubles\Stubs\Petstore\Responses;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use Tests\Doubles\Stubs\Petstore\Responses\Response\ReusableComponentErrorValidationResponse;

class SingleReusableResponses extends ResponsesFactory
{
    public function build(): Responses
    {
        return Responses::create(
            ResponseEntry::create(
                HTTPStatusCode::unprocessableEntity(),
                ReusableComponentErrorValidationResponse::create(),
            ),
        );
    }
}
