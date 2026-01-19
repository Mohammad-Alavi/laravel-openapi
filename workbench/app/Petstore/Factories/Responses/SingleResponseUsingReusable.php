<?php

namespace Workbench\App\Petstore\Factories\Responses;

use MohammadAlavi\LaravelOpenApi\Contracts\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use Workbench\App\Petstore\Reusable\Response\ValidationErrorResponse;

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
