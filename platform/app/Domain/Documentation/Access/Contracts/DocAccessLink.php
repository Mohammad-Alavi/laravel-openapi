<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Access\Contracts;

use Carbon\CarbonInterface;

interface DocAccessLink
{
    public function getId(): int;

    public function getProjectId(): int;

    public function getDocRoleId(): int;

    public function getName(): string;

    public function getTokenHash(): string;

    public function getExpiresAt(): ?CarbonInterface;

    public function getLastUsedAt(): ?CarbonInterface;

    public function isExpired(): bool;

    public function isValid(): bool;

    public function verifyToken(string $plainToken): bool;
}
