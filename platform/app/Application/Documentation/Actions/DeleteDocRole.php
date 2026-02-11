<?php

declare(strict_types=1);

namespace App\Application\Documentation\Actions;

use App\Domain\Documentation\Access\Events\DocRoleDeleted;
use App\Domain\Documentation\Access\Repositories\DocRoleRepository;

final class DeleteDocRole
{
    public function __construct(
        private readonly DocRoleRepository $repository,
    ) {}

    public function execute(int $roleId): void
    {
        $role = $this->repository->findById($roleId);

        $this->repository->delete($roleId);

        DocRoleDeleted::dispatch(
            $role->getProjectId(),
            $role->getName(),
        );
    }
}
