<?php

namespace Tests\Doubles\Stubs\Collectors;

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
