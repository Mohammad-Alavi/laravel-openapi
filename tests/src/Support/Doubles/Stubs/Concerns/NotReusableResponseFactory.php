<?php

namespace Tests\src\Support\Doubles\Stubs\Concerns;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

class NotReusableResponseFactory
{
    public function build(): Response
    {
        return Response::create(Description::create('OK'));
    }
}
