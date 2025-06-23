<?php

namespace Tests\src\Support\Doubles\Stubs;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;

#[Collection('TestCollection')]
#[PathItem]
class CollectibleClass
{
    #[Operation]
    public function __invoke(): string
    {
        return 'example';
    }
}
