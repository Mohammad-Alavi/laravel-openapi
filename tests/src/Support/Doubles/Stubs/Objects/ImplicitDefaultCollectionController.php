<?php

namespace Tests\src\Support\Doubles\Stubs\Objects;

use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;

#[PathItem]
final class ImplicitDefaultCollectionController
{
    public function __invoke(): void
    {
    }
}
