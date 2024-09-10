<?php

namespace Tests\Unit\Attributes\Stubs;

use MohammadAlavi\ObjectOrientedOAS\Objects\Response;
use MohammadAlavi\LaravelOpenApi\Factories\Component\ResponseFactory;

class ResponseFactoryStub extends ResponseFactory
{
    public function build(): Response
    {
        return Response::create();
    }
}