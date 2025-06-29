<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\Response;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ResponseFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Response\Response;

#[Collection(['test', Generator::COLLECTION_DEFAULT])]
class MultiCollectionResponse extends ResponseFactory implements ShouldBeReferenced
{
    public function component(): Response
    {
        return Response::create('OK');
    }
}
