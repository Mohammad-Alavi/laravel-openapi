<?php

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
    public function execute(string $ruleUlid, array $data): DocVisibilityRule
    {
        $existing = $this->repository->findByUlid($ruleUlid);
        $oldVisibility = $existing->getVisibility()->value;

        $rule = $this->repository->update($ruleUlid, $data);

        VisibilityRuleUpdated::dispatch(
            $rule->getProjectId(),
            $rule->getRuleType()->value,
            $oldVisibility,
            $rule->getVisibility()->value,
        );

        return $rule;
    }
}
