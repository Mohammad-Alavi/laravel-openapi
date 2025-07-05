<?php

namespace Tests\src\Support\Doubles\Stubs\Objects;

use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;

#[PathItem]
class InvocableController
{
    public function __invoke(): void
    {
    }
}
