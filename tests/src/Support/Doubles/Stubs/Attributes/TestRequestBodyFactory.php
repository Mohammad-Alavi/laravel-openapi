<?php

namespace Tests\src\Support\Doubles\Stubs\Attributes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;

class TestRequestBodyFactory extends RequestBodyFactory
{
    public function component(): RequestBody
    {
        return RequestBody::create();
    }
}
