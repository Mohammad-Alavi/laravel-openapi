<?php

namespace App\Domain\Documentation\Access\Events;

use Illuminate\Foundation\Events\Dispatchable;

final readonly class VisibilityRuleCreated
{
    use Dispatchable;

    public function __construct(
        public int $projectId,
        public string $ruleType,
        public string $identifier,
        public string $visibility,
    ) {}
}
