<?php

namespace Tests\src\Support\Doubles\Stubs;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

class NotReusableResponseFactory
{
    public function build(): Response
    {
        return Response::create('OK');
    }
}
