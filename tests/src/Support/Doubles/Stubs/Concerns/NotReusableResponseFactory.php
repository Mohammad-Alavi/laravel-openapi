<?php

namespace Tests\src\Support\Doubles\Stubs\Concerns;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

class NotReusableResponseFactory
{
    public function build(): Response
    {
        return Response::create('OK');
    }
}
