<?php

namespace Workbench\App\Laragen\Http\Controllers;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Workbench\App\Documentation\Parameters\CreateUserParameters;
use Workbench\App\Documentation\RequestBodies\CreateUserRequestBody;
use Workbench\App\Documentation\Tags\UserTag;
use Workbench\App\Documentation\UserResponses;
use Workbench\App\Documentation\WorkbenchCollection;
use Workbench\App\Laragen\Http\Requests\CreateUserRequest;

#[Collection(WorkbenchCollection::class)]
#[PathItem]
final readonly class CreateUserController
{
    #[Operation(
        tags: [UserTag::class],
        summary: 'Create User',
        description: 'This operation creates a user.',
        parameters: CreateUserParameters::class,
        requestBody: CreateUserRequestBody::class,
        responses: UserResponses::class,
        operationId: 'createUserOperation',
    )]
    public function __invoke(CreateUserRequest $request): string
    {
        return 'test';
    }
}
