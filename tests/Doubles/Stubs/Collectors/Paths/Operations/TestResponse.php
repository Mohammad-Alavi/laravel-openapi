<?php

namespace Tests\Doubles\Stubs\Collectors\Paths\Operations;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

class TestResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::create('Reusable Response');
    }
}
