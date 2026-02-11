<?php

declare(strict_types=1);

namespace App\Infrastructure\Documentation\Repositories;

use App\Domain\Documentation\Access\Contracts\DocVisibilityRule;
use App\Domain\Documentation\Access\Entities\DocVisibilityRule as DocVisibilityRuleEntity;
use App\Domain\Documentation\Access\Repositories\DocVisibilityRuleRepository;

final class EloquentDocVisibilityRuleRepository implements DocVisibilityRuleRepository
{
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

    public function update(int $id, array $data): DocVisibilityRule
    {
        $rule = DocVisibilityRuleEntity::findOrFail($id);
        $rule->update($data);

        return $rule->refresh();
    }

    public function delete(int $id): void
    {
        DocVisibilityRuleEntity::findOrFail($id)->delete();
    }
}
