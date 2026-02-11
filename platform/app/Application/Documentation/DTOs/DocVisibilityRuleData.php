<?php

declare(strict_types=1);

namespace App\Application\Documentation\DTOs;

use App\Domain\Documentation\Access\Contracts\DocVisibilityRule;
use Spatie\LaravelData\Data;

final class DocVisibilityRuleData extends Data
{
    public function __construct(
        public int $id,
        public string $rule_type,
        public string $identifier,
        public string $visibility,
    ) {}

    public static function fromContract(DocVisibilityRule $rule): self
    {
        return new self(
            id: $rule->getId(),
            rule_type: $rule->getRuleType()->value,
            identifier: $rule->getIdentifier(),
            visibility: $rule->getVisibility()->value,
        );
    }
}
