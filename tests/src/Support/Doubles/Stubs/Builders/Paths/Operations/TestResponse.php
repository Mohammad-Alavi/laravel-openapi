<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Paths\Operations;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

class TestResponse extends ResponseFactory
{
    public function component(): Response
    {
        return Response::create('Reusable Response');
    }
}
