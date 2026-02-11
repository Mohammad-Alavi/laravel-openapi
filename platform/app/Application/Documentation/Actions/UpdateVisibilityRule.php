<?php

declare(strict_types=1);

namespace App\Application\Documentation\Actions;

use App\Domain\Documentation\Access\Contracts\DocVisibilityRule;
use App\Domain\Documentation\Access\Events\VisibilityRuleUpdated;
use App\Domain\Documentation\Access\Repositories\DocVisibilityRuleRepository;

final class UpdateVisibilityRule
{
    public function __construct(
        private readonly DocVisibilityRuleRepository $repository,
    ) {}

    /** @param array{rule_type?: string, identifier?: string, visibility?: string} $data */
    public function execute(int $ruleId, array $data): DocVisibilityRule
    {
        $existing = $this->repository->findById($ruleId);
        $oldVisibility = $existing->getVisibility()->value;

        $rule = $this->repository->update($ruleId, $data);

        VisibilityRuleUpdated::dispatch(
            $rule->getProjectId(),
            $rule->getRuleType()->value,
            $oldVisibility,
            $rule->getVisibility()->value,
        );

        return $rule;
    }
}
