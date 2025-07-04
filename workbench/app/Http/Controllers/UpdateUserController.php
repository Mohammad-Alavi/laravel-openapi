<?php

namespace Workbench\App\Http\Controllers;

use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Workbench\App\Documentation\Responses\UserResponse;

#[PathItem]
final readonly class UpdateUserController
{
    #[Operation(
        summary: 'Update User',
        description: 'This operation updates a user.',
        responses: UserResponse::class,
        operationId: 'updateUserOperation',
    )]
    public function test(): string
    {
        return 'test';
    }
}
