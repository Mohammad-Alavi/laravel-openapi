<?php

namespace App\Application\Documentation\Actions;

use App\Domain\Documentation\Access\Events\VisibilityRuleDeleted;
use App\Domain\Documentation\Access\Repositories\DocVisibilityRuleRepository;

final class DeleteVisibilityRule
{
    public function __construct(
        private readonly DocVisibilityRuleRepository $repository,
    ) {}

    public function execute(string $ruleUlid): void
    {
        $rule = $this->repository->findByUlid($ruleUlid);

        $this->repository->delete($ruleUlid);

        VisibilityRuleDeleted::dispatch(
            $rule->getProjectId(),
            $rule->getRuleType()->value,
            $rule->getIdentifier(),
        );
    }
}
