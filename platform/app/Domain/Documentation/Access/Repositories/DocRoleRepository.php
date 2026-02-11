<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Access\Repositories;

use App\Domain\Documentation\Access\Contracts\DocRole;

interface DocRoleRepository
{
    public function findByUlid(string $ulid): ?DocRole;

    /** @return list<DocRole> */
    public function findByProjectId(int $projectId): array;

    /** @param array{project_id: int, name: string, scopes: list<string>, is_default?: bool} $data */
    public function create(array $data): DocRole;

    /** @param array{name?: string, scopes?: list<string>, is_default?: bool} $data */
    public function update(string $ulid, array $data): DocRole;

    public function delete(string $ulid): void;
}
