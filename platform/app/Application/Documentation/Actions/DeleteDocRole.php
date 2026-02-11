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

    public function execute(string $roleUlid): void
    {
        $role = $this->repository->findByUlid($roleUlid);

        $this->repository->delete($roleUlid);

        DocRoleDeleted::dispatch(
            $role->getProjectId(),
            $role->getName(),
        );
    }
}
