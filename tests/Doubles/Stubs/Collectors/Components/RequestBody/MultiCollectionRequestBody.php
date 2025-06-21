<?php

namespace Tests\Doubles\Stubs\Collectors\Components\RequestBody;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;

#[Collection(['test', Generator::COLLECTION_DEFAULT])]
class MultiCollectionRequestBody extends RequestBodyFactory
{
    public function component(): RequestBody
    {
        return RequestBody::create();
    }
}
