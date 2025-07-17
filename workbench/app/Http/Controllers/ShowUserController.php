<?php

namespace Workbench\App\Http\Controllers;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Workbench\App\Documentation\Responses\UserResponse;
use Workbench\App\Documentation\Tags\UserTag;

#[PathItem]
final readonly class ShowUserController
{
    #[Operation(
        tags: UserTag::class,
        summary: 'Show User',
        description: 'This operation retrieves a user by ID.',
        responses: UserResponse::class,
        operationId: 'showUserOperation',
    )]
    #[Collection('Workbench')]
    public function __invoke(): string
    {
        return 'test';
    }
}
