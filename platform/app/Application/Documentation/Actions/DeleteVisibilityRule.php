<?php

declare(strict_types=1);

namespace App\Application\Documentation\Actions;

use App\Domain\Documentation\Access\Events\VisibilityRuleDeleted;
use App\Domain\Documentation\Access\Repositories\DocVisibilityRuleRepository;

final class DeleteVisibilityRule
{
    public function __construct(
        private readonly DocVisibilityRuleRepository $repository,
    ) {}

    public function execute(int $ruleId): void
    {
        $rule = $this->repository->findById($ruleId);

        $this->repository->delete($ruleId);

        VisibilityRuleDeleted::dispatch(
            $rule->getProjectId(),
            $rule->getRuleType()->value,
            $rule->getIdentifier(),
        );
    }
}
