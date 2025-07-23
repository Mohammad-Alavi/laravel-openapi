<?php

namespace Workbench\App\Laragen\Http\Controllers;

use MohammadAlavi\LaravelOpenApi\Attributes\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Workbench\App\Documentation\Callbacks\UserUpdatedCallback;
use Workbench\App\Documentation\Parameters\UpdateUserParameters;
use Workbench\App\Documentation\RequestBodies\UpdateUserRequestBody;
use Workbench\App\Documentation\Tags\UserTag;
use Workbench\App\Documentation\UpdateUserSecurity;
use Workbench\App\Documentation\UserResponses;
use Workbench\App\Documentation\WorkbenchCollection;
use Workbench\App\Laragen\Http\Requests\CreateUserRequest;
use Workbench\App\Models\User;

#[Collection(WorkbenchCollection::class)]
#[PathItem]
final readonly class UpdateUserController
{
    #[Operation(
        tags: [UserTag::class],
        summary: 'Update User',
        description: 'This operation updates a user.',
        parameters: UpdateUserParameters::class,
        requestBody: UpdateUserRequestBody::class,
        responses: UserResponses::class,
        callbacks: UserUpdatedCallback::class,
        security: UpdateUserSecurity::class,
        operationId: 'updateUserOperation',
    )]
    public function __invoke(CreateUserRequest $request): string
    {
        return 'test';
    }

    public function methodWithParams(int $id, User $user, string $slug, $noTypeParam, bool $flag, User $author_id)
    {
    }
}
