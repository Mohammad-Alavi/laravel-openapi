<?php

namespace Tests\Doubles\Stubs\Collectors\Components\Response;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

#[Collection('test')]
class ExplicitCollectionResponse extends ReusableResponseFactory
{
    public function build(): Response
    {
        return Response::create(Description::create('OK'));
    }
}
