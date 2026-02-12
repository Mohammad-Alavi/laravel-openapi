<?php

namespace App\Domain\Documentation\Access\ValueObjects;

use App\Domain\Documentation\Access\Contracts\DocRole;

final readonly class ViewerContext
{
    private function __construct(
        private bool $isOwner,
        private ?DocRole $role,
    ) {}

    public static function owner(): self
    {
        return new self(isOwner: true, role: null);
    }

    public static function withRole(DocRole $role): self
    {
        return new self(isOwner: false, role: $role);
    }

    public static function anonymous(): self
    {
        return new self(isOwner: false, role: null);
    }

    public function isOwner(): bool
    {
        return $this->isOwner;
    }

    public function isAnonymous(): bool
    {
        return ! $this->isOwner && $this->role === null;
    }

    public function hasRole(): bool
    {
        return $this->role !== null;
    }

    public function role(): ?DocRole
    {
        return $this->role;
    }
}
