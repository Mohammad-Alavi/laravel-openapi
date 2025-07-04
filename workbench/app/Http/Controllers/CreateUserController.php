<?php

namespace Workbench\App\Http\Controllers;

use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Workbench\App\Documentation\Responses\UserResponse;

#[PathItem]
final readonly class CreateUserController
{
    #[Operation(
        summary: 'Create User',
        description: 'This operation creates a user.',
        responses: UserResponse::class,
        operationId: 'createUserOperation',
    )]
    public function test(): string
    {
        return 'test';
    }
}
