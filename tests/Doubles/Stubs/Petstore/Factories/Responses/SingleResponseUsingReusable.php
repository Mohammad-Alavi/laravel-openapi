<?php

namespace Tests\Doubles\Stubs\Petstore\Factories\Responses;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use Tests\Doubles\Stubs\Petstore\Reusable\Response\ValidationErrorResponse;

class SingleResponseUsingReusable extends ResponsesFactory
{
    public function build(): Responses
    {
        return Responses::create(
            ResponseEntry::create(
                HTTPStatusCode::unprocessableEntity(),
                ValidationErrorResponse::create(),
            ),
        );
    }
}
