<?php

namespace Tests\src\Support\Doubles\Stubs\Collectors;

use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;

#[PathItem]
class ControllerWithoutOperationStub
{
    public function __invoke(): string
    {
        return 'example';
    }
}
