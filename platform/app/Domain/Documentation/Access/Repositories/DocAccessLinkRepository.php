<?php

namespace App\Domain\Documentation\Access\Repositories;

use App\Domain\Documentation\Access\Contracts\DocAccessLink;

interface DocAccessLinkRepository
{
    /** @return list<DocAccessLink> */
    public function findByProjectId(int $projectId): array;

    public function findByToken(string $hashedToken): ?DocAccessLink;

    /** @param array{project_id: int, doc_role_id: int, name: string, token: string, expires_at?: string|null} $data */
    public function create(array $data): DocAccessLink;

    public function delete(string $ulid): void;

    public function touchLastUsed(string $ulid): void;
}
