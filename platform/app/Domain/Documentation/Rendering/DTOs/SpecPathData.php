<?php

namespace App\Domain\Documentation\Rendering\DTOs;

final readonly class SpecPathData
{
    public function __construct(
        public string $path,
        /** @var list<string> */
        public array $methods,
    ) {}
}
