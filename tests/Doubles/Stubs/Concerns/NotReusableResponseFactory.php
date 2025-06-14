<?php

namespace Tests\Doubles\Stubs\Concerns;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

class NotReusableResponseFactory
{
    public function build(): Response
    {
        return Response::create(Description::create('OK'));
    }
}
