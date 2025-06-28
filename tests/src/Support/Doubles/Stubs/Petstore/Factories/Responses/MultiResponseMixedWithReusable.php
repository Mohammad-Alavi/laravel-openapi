<?php

namespace Tests\src\Support\Doubles\Stubs\Petstore\Factories\Responses;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\MediaType\MediaType;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Content\ContentEntry;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;
use Tests\src\Support\Doubles\Stubs\Petstore\Reusable\Response\ValidationErrorResponse;
use Tests\src\Support\Doubles\Stubs\Petstore\Reusable\Schema\PetSchema;

class MultiResponseMixedWithReusable implements ResponsesFactory
{
    public function build(): Responses
    {
        return Responses::create(
            ResponseEntry::create(
                HTTPStatusCode::unprocessableEntity(),
                ValidationErrorResponse::create(),
            ),
            ResponseEntry::create(
                HTTPStatusCode::ok(),
                Response::create(Description::create('Resource created'))
                    ->content(
                        ContentEntry::json(
                            MediaType::create()->schema(PetSchema::create()),
                        ),
                    ),
            ),
            ResponseEntry::create(
                HTTPStatusCode::forbidden(),
                Response::create(Description::create('Forbidden')),
            ),
        );
    }
}
