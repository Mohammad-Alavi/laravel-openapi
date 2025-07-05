<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\RequestBody;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody\RequestBody;

#[Collection('test')]
class ExplicitCollectionRequestBody extends RequestBodyFactory implements ShouldBeReferenced
{
    public function component(): RequestBody
    {
        return RequestBody::create();
    }
}
