<?php

declare(strict_types=1);

namespace App\Infrastructure\Documentation\Repositories;

use App\Domain\Documentation\Access\Contracts\DocVisibilityRule;
use App\Domain\Documentation\Access\Entities\DocVisibilityRule as DocVisibilityRuleEntity;
use App\Domain\Documentation\Access\Repositories\DocVisibilityRuleRepository;

final class EloquentDocVisibilityRuleRepository implements DocVisibilityRuleRepository
{
    public function findByUlid(string $ulid): ?DocVisibilityRule
    {
        return DocVisibilityRuleEntity::where('ulid', $ulid)->first();
    }

    public function findByProjectId(int $projectId): array
    {
        return DocVisibilityRuleEntity::where('project_id', $projectId)
            ->orderBy('rule_type')
            ->orderBy('identifier')
            ->get()
            ->all();
    }

    public function create(array $data): DocVisibilityRule
    {
        return DocVisibilityRuleEntity::create($data);
    }

    public function update(string $ulid, array $data): DocVisibilityRule
    {
        $rule = DocVisibilityRuleEntity::where('ulid', $ulid)->firstOrFail();
        $rule->update($data);

        return $rule->refresh();
    }

    public function delete(string $ulid): void
    {
        DocVisibilityRuleEntity::where('ulid', $ulid)->firstOrFail()->delete();
    }
}
