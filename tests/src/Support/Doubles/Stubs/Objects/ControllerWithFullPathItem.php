<?php

namespace Tests\src\Support\Doubles\Stubs\Objects;

use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Tests\src\Support\Doubles\Stubs\Attributes\TestParametersFactory;
use Tests\src\Support\Doubles\Stubs\Servers\ServerWithoutVariables;

#[PathItem(
    summary: 'Test summary',
    description: 'Test description',
    servers: ServerWithoutVariables::class,
    parameters: TestParametersFactory::class,
)]
class ControllerWithFullPathItem
{
    #[Operation(operationId: 'testOperation')]
    public function index(): void
    {
    }
}
