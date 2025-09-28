<?php

namespace Tests\src\Support\Doubles\Stubs\Objects;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use MohammadAlavi\LaravelOpenApi\Generator;

#[PathItem]
#[Collection('some-other-collection')]
final class ExplicitOverriddenDefaultCollectionControllerAction
{
    #[Collection(Generator::COLLECTION_DEFAULT)]
    public function __invoke(): void
    {
    }
}
