<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Access\Contracts;

use App\Domain\Documentation\Access\Enums\DocVisibility;

interface DocSetting
{
    public function getId(): int;

    public function getProjectId(): int;

    public function getVisibility(): DocVisibility;

    public function isPublic(): bool;

    public function isPrivate(): bool;
}
