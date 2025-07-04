<?php

namespace Workbench\App\Http\Controllers;

use Workbench\App\Documentation\Responses\CreateUserResponse;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;

#[PathItem]
final readonly class UserController
{
    #[Operation(
        summary: 'Test Operation',
        description: 'This is a test operation.',
        responses: CreateUserResponse::class,
        operationId: 'testOperation',
    )]
    public function test(): string
    {
        return 'test';
    }
}
