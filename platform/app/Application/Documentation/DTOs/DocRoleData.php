<?php

namespace App\Application\Documentation\DTOs;

use App\Domain\Documentation\Access\Contracts\DocRole;
use Spatie\LaravelData\Data;

final class DocRoleData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        /** @var list<string> */
        public array $scopes,
        public bool $is_default,
    ) {}

    public static function fromContract(DocRole $role): self
    {
        return new self(
            id: $role->getUlid(),
            name: $role->getName(),
            scopes: $role->getScopes()->toArray(),
            is_default: $role->isDefault(),
        );
    }
}
