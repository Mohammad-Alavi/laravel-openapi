<?php

namespace Tests\src\Support\Doubles\Stubs\Collectors;

use MohammadAlavi\LaravelOpenApi\Attributes\Operation;

class ControllerWithoutPathItemStub
{
    #[Operation]
    public function __invoke(): string
    {
        return 'example';
    }
}
