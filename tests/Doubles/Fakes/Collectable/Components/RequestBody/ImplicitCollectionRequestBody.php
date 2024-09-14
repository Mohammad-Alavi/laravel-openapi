<?php

namespace Tests\Doubles\Fakes\Collectable\Components\RequestBody;

use MohammadAlavi\LaravelOpenApi\Contracts\Reusable;
use MohammadAlavi\LaravelOpenApi\Factories\Component\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOAS\Objects\RequestBody;

class ImplicitCollectionRequestBody extends RequestBodyFactory implements Reusable
{
    public function build(): RequestBody
    {
        return RequestBody::create();
    }
}
