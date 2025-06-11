<?php

namespace Tests\Doubles\Stubs\Collectors\Paths\Operations;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ReusableResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

class TestReusableResponse extends ReusableResponseFactory
{
    public function build(): Response
    {
        return Response::create('Reusable Response');
    }
}
