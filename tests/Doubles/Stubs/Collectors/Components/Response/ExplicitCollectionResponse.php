<?php

namespace Tests\Doubles\Stubs\Collectors\Components\Response;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Contracts\Reusable;
use MohammadAlavi\LaravelOpenApi\Factories\Component\ResponseFactory;
use MohammadAlavi\LaravelOpenApi\oooas\Schema\Objects\Response;

#[Collection('test')]
class ExplicitCollectionResponse extends ResponseFactory implements Reusable
{
    public function build(): Response
    {
        return Response::create('test collection Response');
    }
}
