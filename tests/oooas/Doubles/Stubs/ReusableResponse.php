<?php

namespace Tests\oooas\Doubles\Stubs;

use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

class ReusableResponse extends ReusableResponseFactory
{
    public function build(): Response
    {
        return Response::ok('This is a reusable response');
    }
}
