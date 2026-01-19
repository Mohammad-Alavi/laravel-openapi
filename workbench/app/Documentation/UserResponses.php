<?php

namespace Workbench\App\Documentation;

use MohammadAlavi\LaravelOpenApi\Contracts\Factories\ResponsesFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Fields\HTTPStatusCode;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Responses;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Responses\Support\ResponseEntry;
use Workbench\App\Documentation\Responses\UserResponse;
use Workbench\App\Documentation\Shared\Responses\UnprocessableEntityResponse;

final readonly class UserResponses implements ResponsesFactory
{
    public function build(): Responses
    {
        return Responses::create(
            ResponseEntry::create(
                HTTPStatusCode::ok(),
                UserResponse::create(),
            ),
            ResponseEntry::create(
                HTTPStatusCode::unprocessableEntity(),
                UnprocessableEntityResponse::create(),
            ),
        );
    }
}
