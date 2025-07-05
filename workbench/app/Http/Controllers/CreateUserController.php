<?php

namespace Workbench\App\Http\Controllers;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Workbench\App\Documentation\Responses\UserResponse;

#[PathItem]
#[Collection('laragen')]
final readonly class CreateUserController
{
    #[Operation(
        summary: 'Create User',
        description: 'This operation creates a user.',
        responses: UserResponse::class,
        operationId: 'createUserOperation',
    )]
    public function __invoke(): string
    {
        return 'test';
    }
}
