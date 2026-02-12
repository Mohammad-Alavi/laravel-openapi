<?php

namespace App\Domain\Documentation\Access\Events;

use Illuminate\Foundation\Events\Dispatchable;

final readonly class VisibilityRuleUpdated
{
    use Dispatchable;

    public function __construct(
        public int $projectId,
        public string $ruleType,
        public string $oldVisibility,
        public string $newVisibility,
    ) {}
}
