<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Access\Events;

use Illuminate\Foundation\Events\Dispatchable;

final readonly class AccessLinkRevoked
{
    use Dispatchable;

    public function __construct(
        public int $projectId,
        public string $linkName,
    ) {}
}
