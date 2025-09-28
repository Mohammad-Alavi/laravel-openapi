<?php

namespace Tests\src\Support\Doubles\Stubs\Objects;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Workbench\App\Petstore\Factories\Responses\SingleResponseUsingReusable;

#[PathItem]
#[Collection('example')]
class MultiActionController
{
    #[Operation(
        responses: SingleResponseUsingReusable::class,
        operationId: 'anotherExample',
    )]
    public function anotherExample(): void
    {
    }

    #[Operation]
    #[Collection('another-collection')]
    public function example(): void
    {
    }
}
