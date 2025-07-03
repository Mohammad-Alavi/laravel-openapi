<?php

namespace Workbench\App\Http\Controllers;

use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Tests\src\Support\Doubles\Stubs\Attributes\TestResponsesFactory;

#[PathItem]
class UserController
{
    #[Operation(
        summary: 'Test Operation',
        description: 'This is a test operation.',
        responses: TestResponsesFactory::class,
        operationId: 'testOperation',
    )]
    public function __invoke()
    {
        return 'test';
    }
}
