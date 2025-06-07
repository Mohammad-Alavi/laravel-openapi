<?php

namespace Tests\Doubles\Stubs\Concerns;

use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

class NotReusableResponseFactory implements ResponseFactory
{
    public function build(): Response
    {
        return Response::ok();
    }
}
