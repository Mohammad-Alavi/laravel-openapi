<?php

namespace Tests\src\Support\Doubles\Stubs\Objects;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;

#[PathItem]
#[Collection('some-other-collection')]
final class ExplicitOverriddenDefaultCollectionControllerAction
{
    #[Collection(Collection::DEFAULT)]
    public function __invoke(): void
    {
    }
}
