<?php

namespace Tests\src\Support\Doubles\Stubs\Collectors\Components\Response;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Description;

#[Collection('test')]
class ExplicitCollectionResponse extends ResponseFactory
{
    public function component(): Response
    {
        return Response::create(Description::create('OK'));
    }
}
