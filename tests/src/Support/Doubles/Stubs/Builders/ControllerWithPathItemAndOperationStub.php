<?php

namespace Tests\src\Support\Doubles\Stubs\Builders;

use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;

#[PathItem]
class ControllerWithPathItemAndOperationStub
{
    #[Operation]
    public function __invoke(): string
    {
        return 'example';
    }
}
