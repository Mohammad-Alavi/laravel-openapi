<?php

namespace Tests\Unit\Attributes\Stubs;

use MohammadAlavi\ObjectOrientedOAS\Objects\RequestBody;
use MohammadAlavi\LaravelOpenApi\Factories\Component\RequestBodyFactory;

class RequestBodyFactoryStub extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create();
    }
}