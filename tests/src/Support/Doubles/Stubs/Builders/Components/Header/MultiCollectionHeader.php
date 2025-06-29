<?php

namespace Tests\src\Support\Doubles\Stubs\Builders\Components\Header;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\HeaderFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Header\Header;

#[Collection(['test', Generator::COLLECTION_DEFAULT])]
class MultiCollectionHeader extends HeaderFactory implements ShouldBeReferenced
{
    public function component(): Header
    {
        return Header::create();
    }
}
