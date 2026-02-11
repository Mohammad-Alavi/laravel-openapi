<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Access\Contracts;

use App\Domain\Documentation\Access\Enums\RuleType;
use App\Domain\Documentation\Access\ValueObjects\ScopeCollection;

interface DocRole
{
    public function getId(): int;

    public function getProjectId(): int;

    public function getName(): string;

    public function getScopes(): ScopeCollection;

    public function isDefault(): bool;

    public function grantsAccessTo(RuleType $ruleType, string $identifier): bool;
}
