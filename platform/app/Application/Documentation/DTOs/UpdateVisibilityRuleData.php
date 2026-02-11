<?php

declare(strict_types=1);

namespace App\Application\Documentation\DTOs;

use App\Domain\Documentation\Access\Enums\EndpointVisibility;
use App\Domain\Documentation\Access\Enums\RuleType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class UpdateVisibilityRuleData extends Data
{
    public function __construct(
        public RuleType|Optional $rule_type,

        public string|Optional $identifier,

        public EndpointVisibility|Optional $visibility,
    ) {}
}
