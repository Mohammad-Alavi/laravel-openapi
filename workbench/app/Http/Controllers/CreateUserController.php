<?php

namespace Workbench\App\Http\Controllers;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Workbench\App\Documentation\Responses\UserResponse;
use Workbench\App\Documentation\Tags\UserTag;
use Workbench\App\Http\Requests\CreateUserRequest;

#[Collection('Workbench')]
#[PathItem]
final readonly class CreateUserController
{
    #[Operation(
        tags: [UserTag::class],
        summary: 'Create User',
        description: 'This operation creates a user.',
        responses: UserResponse::class,
        operationId: 'createUserOperation',
    )]
    public function __invoke(CreateUserRequest $request): string
    {
        return 'test';
    }
}
