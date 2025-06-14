<?php

namespace Tests\Doubles\Stubs\Attributes;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ReusableRequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;

class RequestBodyFactory extends ReusableRequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create();
    }
}
