<?php

declare(strict_types=1);

namespace App\Domain\Documentation\Rendering\DTOs;

final readonly class SpecPathData
{
    public function __construct(
        public string $path,
        /** @var list<string> */
        public array $methods,
    ) {}
}
