<?php

namespace Tests\Doubles\Stubs\Collectors\Components\Response;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableResponseFactory;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

#[Collection(['test', Generator::COLLECTION_DEFAULT])]
class MultiCollectionResponse extends ReusableResponseFactory
{
    public function build(): Response
    {
        return Response::create(Description::create('OK'));
    }
}
