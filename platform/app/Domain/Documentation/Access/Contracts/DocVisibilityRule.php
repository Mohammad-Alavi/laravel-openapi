<?php

namespace App\Domain\Documentation\Access\Contracts;

use App\Domain\Documentation\Access\Enums\EndpointVisibility;
use App\Domain\Documentation\Access\Enums\RuleType;

interface DocVisibilityRule
{
    public function getId(): int;

    public function getUlid(): string;

    public function getProjectId(): int;

    public function getRuleType(): RuleType;

    public function getIdentifier(): string;

    public function getVisibility(): EndpointVisibility;
}
