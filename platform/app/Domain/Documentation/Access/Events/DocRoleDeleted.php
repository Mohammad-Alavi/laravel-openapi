<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Access\Events;

use Illuminate\Foundation\Events\Dispatchable;

final readonly class DocRoleDeleted
{
    use Dispatchable;

    public function __construct(
        public int $projectId,
        public string $roleName,
    ) {}
}
