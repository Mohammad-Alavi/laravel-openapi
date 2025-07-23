<?php

namespace Workbench\App\Laragen\Http\Controllers;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Workbench\App\Documentation\Tags\UserTag;
use Workbench\App\Documentation\UserResponses;
use Workbench\App\Documentation\WorkbenchCollection;

#[PathItem]
final readonly class ShowUserController
{
    #[Operation(
        tags: UserTag::class,
        summary: 'Show User',
        description: 'This operation retrieves a user by ID.',
        responses: UserResponses::class,
        operationId: 'showUserOperation',
    )]
    #[Collection(WorkbenchCollection::class)]
    public function __invoke(): string
    {
        return 'test';
    }
}
