<?php

declare(strict_types=1);

namespace App\Application\Documentation\Actions;

use App\Domain\Documentation\Access\Contracts\DocRole;
use App\Domain\Documentation\Access\Events\DocRoleCreated;
use App\Domain\Documentation\Access\Repositories\DocRoleRepository;
use App\Domain\Documentation\Access\ValueObjects\ScopeCollection;

final class CreateDocRole
{
    public function __construct(
        private readonly DocRoleRepository $repository,
    ) {}

    /** @param array{name: string, scopes: list<string>, is_default?: bool} $data */
    public function execute(int $projectId, array $data): DocRole
    {
        $role = $this->repository->create([
            'project_id' => $projectId,
            ...$data,
        ]);

        $scopes = ScopeCollection::fromArray($data['scopes']);

        DocRoleCreated::dispatch(
            $projectId,
            $role->getName(),
            $scopes->count(),
            $scopes->hasWildcards(),
        );

        return $role;
    }
}
