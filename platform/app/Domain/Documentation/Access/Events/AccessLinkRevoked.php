<?php

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
