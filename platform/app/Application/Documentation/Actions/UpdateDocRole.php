<?php

declare(strict_types=1);

namespace App\Application\Documentation\Actions;

use App\Domain\Documentation\Access\Contracts\DocRole;
use App\Domain\Documentation\Access\Events\DocRoleUpdated;
use App\Domain\Documentation\Access\Repositories\DocRoleRepository;

final class UpdateDocRole
{
    public function __construct(
        private readonly DocRoleRepository $repository,
    ) {}

    /** @param array{name?: string, scopes?: list<string>, is_default?: bool} $data */
    public function execute(string $roleUlid, array $data): DocRole
    {
        $existing = $this->repository->findByUlid($roleUlid);
        $scopesChanged = isset($data['scopes']) && $data['scopes'] !== $existing->getScopes()->toArray();

        $role = $this->repository->update($roleUlid, $data);

        DocRoleUpdated::dispatch(
            $role->getProjectId(),
            $role->getName(),
            $scopesChanged,
        );

        return $role;
    }
}
