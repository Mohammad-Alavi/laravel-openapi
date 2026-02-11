<?php

declare(strict_types=1);

namespace App\Infrastructure\Documentation\Repositories;

use App\Domain\Documentation\Access\Contracts\DocAccessLink;
use App\Domain\Documentation\Access\Entities\DocAccessLink as DocAccessLinkEntity;
use App\Domain\Documentation\Access\Repositories\DocAccessLinkRepository;

final class EloquentDocAccessLinkRepository implements DocAccessLinkRepository
{
    public function findByProjectId(int $projectId): array
    {
        return DocAccessLinkEntity::where('project_id', $projectId)
            ->with('docRole')
            ->orderBy('name')
            ->get()
            ->all();
    }

    public function findByToken(string $hashedToken): ?DocAccessLink
    {
        return DocAccessLinkEntity::where('token', $hashedToken)
            ->with('docRole')
            ->first();
    }

    public function create(array $data): DocAccessLink
    {
        return DocAccessLinkEntity::create($data);
    }

    public function delete(string $ulid): void
    {
        DocAccessLinkEntity::where('ulid', $ulid)->firstOrFail()->delete();
    }

    public function touchLastUsed(string $ulid): void
    {
        DocAccessLinkEntity::where('ulid', $ulid)->update(['last_used_at' => now()]);
    }
}
