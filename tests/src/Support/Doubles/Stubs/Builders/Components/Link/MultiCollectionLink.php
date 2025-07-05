<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\Link;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\LinkFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Link\Link;

#[Collection(['test', Generator::COLLECTION_DEFAULT])]
class MultiCollectionLink extends LinkFactory implements ShouldBeReferenced
{
    public function component(): Link
    {
        return Link::create();
    }
}
