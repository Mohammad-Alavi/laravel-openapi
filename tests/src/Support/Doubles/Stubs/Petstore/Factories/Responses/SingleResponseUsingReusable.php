<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Factories\Responses;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use Tests\src\Support\Doubles\Stubs\Petstore\Reusable\Response\ValidationErrorResponse;

class SingleResponseUsingReusable implements ResponsesFactory
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
