<?php

namespace App\Domain\Documentation\Rendering\DTOs;

final readonly class SpecTagData
{
    public function __construct(
        public string $name,
        public ?string $description,
    ) {}
}
