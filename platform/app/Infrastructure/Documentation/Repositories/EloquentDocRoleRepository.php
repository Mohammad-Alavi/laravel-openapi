<?php

namespace App\Infrastructure\Documentation\Repositories;

use App\Domain\Documentation\Access\Contracts\DocRole;
use App\Domain\Documentation\Access\Entities\DocRole as DocRoleEntity;
use App\Domain\Documentation\Access\Repositories\DocRoleRepository;

final class EloquentDocRoleRepository implements DocRoleRepository
{
    public function findByUlid(string $ulid): ?DocRole
    {
        return DocRoleEntity::where('ulid', $ulid)->first();
    }

    public function findByProjectId(int $projectId): array
    {
        return DocRoleEntity::where('project_id', $projectId)
            ->orderBy('name')
            ->get()
            ->all();
    }

    public function create(array $data): DocRole
    {
        return DocRoleEntity::create($data);
    }

    public function update(string $ulid, array $data): DocRole
    {
        $role = DocRoleEntity::where('ulid', $ulid)->firstOrFail();
        $role->update($data);

        return $role->refresh();
    }

    public function delete(string $ulid): void
    {
        DocRoleEntity::where('ulid', $ulid)->firstOrFail()->delete();
    }
}
