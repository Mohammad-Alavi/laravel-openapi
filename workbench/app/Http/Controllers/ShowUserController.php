<?php

namespace Workbench\App\Http\Controllers;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Workbench\App\Documentation\Responses\UserResponse;

#[PathItem]
#[Collection('laragen')]
final readonly class ShowUserController
{
    #[Operation(
        summary: 'Show User',
        description: 'This operation retrieves a user by ID.',
        responses: UserResponse::class,
        operationId: 'showUserOperation',
    )]
    public function __invoke(): string
    {
        return 'test';
    }
}
