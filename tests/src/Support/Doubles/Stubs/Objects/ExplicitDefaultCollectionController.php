<?php

namespace Tests\src\Support\Doubles\Stubs\Objects;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use MohammadAlavi\LaravelOpenApi\Generator;

#[PathItem]
#[Collection(Generator::COLLECTION_DEFAULT)]
final class ExplicitDefaultCollectionController
{
    public function __invoke(): void
    {
    }
}
