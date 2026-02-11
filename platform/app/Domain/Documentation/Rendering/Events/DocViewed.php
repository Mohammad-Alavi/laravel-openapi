<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Rendering\Events;

use Illuminate\Foundation\Events\Dispatchable;

final readonly class DocViewed
{
    use Dispatchable;

    public function __construct(
        public int $projectId,
        public string $viewerType,
        public ?string $roleName,
        public int $endpointCount,
    ) {}
}
