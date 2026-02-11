<?php

declare(strict_types=1);

namespace App\Infrastructure\Documentation\Repositories;

use App\Domain\Documentation\Access\Contracts\DocRole;
use App\Domain\Documentation\Access\Entities\DocRole as DocRoleEntity;
use App\Domain\Documentation\Access\Repositories\DocRoleRepository;

final class EloquentDocRoleRepository implements DocRoleRepository
{
    public function findById(int $id): ?DocRole
    {
        return DocRoleEntity::find($id);
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

    public function update(int $id, array $data): DocRole
    {
        $role = DocRoleEntity::findOrFail($id);
        $role->update($data);

        return $role->refresh();
    }

    public function delete(int $id): void
    {
        DocRoleEntity::findOrFail($id)->delete();
    }
}
