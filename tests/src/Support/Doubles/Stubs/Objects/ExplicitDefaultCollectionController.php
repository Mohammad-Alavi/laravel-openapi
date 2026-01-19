<?php

namespace Tests\src\Support\Doubles\Stubs\Objects;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;

#[PathItem]
#[Collection(Collection::DEFAULT)]
final class ExplicitDefaultCollectionController
{
    public function __invoke(): void
    {
    }
}
