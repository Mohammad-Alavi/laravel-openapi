<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Access\Repositories;

use App\Domain\Documentation\Access\Contracts\DocVisibilityRule;

interface DocVisibilityRuleRepository
{
    public function findById(int $id): ?DocVisibilityRule;

    /** @return list<DocVisibilityRule> */
    public function findByProjectId(int $projectId): array;

    /** @param array{project_id: int, rule_type: string, identifier: string, visibility: string} $data */
    public function create(array $data): DocVisibilityRule;

    /** @param array{rule_type?: string, identifier?: string, visibility?: string} $data */
    public function update(int $id, array $data): DocVisibilityRule;

    public function delete(int $id): void;
}
