<?php

namespace App\Application\Documentation\DTOs;

use App\Domain\Documentation\Access\Contracts\DocAccessLink;
use Spatie\LaravelData\Data;

final class DocAccessLinkData extends Data
{
    public function __construct(
        public string $id,
        public string $doc_role_id,
        public string $name,
        public ?string $expires_at,
        public ?string $last_used_at,
        public bool $is_expired,
    ) {}

    public static function fromContract(DocAccessLink $link): self
    {
        return new self(
            id: $link->getUlid(),
            doc_role_id: $link->getDocRoleUlid(),
            name: $link->getName(),
            expires_at: $link->getExpiresAt()?->toIso8601String(),
            last_used_at: $link->getLastUsedAt()?->toIso8601String(),
            is_expired: $link->isExpired(),
        );
    }
}
