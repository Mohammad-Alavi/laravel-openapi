<?php

declare(strict_types=1);

namespace App\Application\Documentation\DTOs;

use App\Domain\Documentation\Access\Contracts\DocRole;
use Spatie\LaravelData\Data;

final class DocRoleData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        /** @var list<string> */
        public array $scopes,
        public bool $is_default,
    ) {}

    public static function fromContract(DocRole $role): self
    {
        return new self(
            id: $role->getId(),
            name: $role->getName(),
            scopes: $role->getScopes()->toArray(),
            is_default: $role->isDefault(),
        );
    }
}
