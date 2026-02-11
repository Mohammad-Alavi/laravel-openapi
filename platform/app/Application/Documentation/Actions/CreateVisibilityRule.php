<?php

declare(strict_types=1);

namespace App\Application\Documentation\Actions;

use App\Domain\Documentation\Access\Contracts\DocVisibilityRule;
use App\Domain\Documentation\Access\Events\VisibilityRuleCreated;
use App\Domain\Documentation\Access\Repositories\DocVisibilityRuleRepository;

final class CreateVisibilityRule
{
    public function __construct(
        private readonly DocVisibilityRuleRepository $repository,
    ) {}

    /** @param array{project_id: int, rule_type: string, identifier: string, visibility: string} $data */
    public function execute(array $data): DocVisibilityRule
    {
        $rule = $this->repository->create($data);

        VisibilityRuleCreated::dispatch(
            $rule->getProjectId(),
            $rule->getRuleType()->value,
            $rule->getIdentifier(),
            $rule->getVisibility()->value,
        );

        return $rule;
    }
}
