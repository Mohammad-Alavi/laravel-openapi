<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\Annotations;

final readonly class DetectedBodyParam
{
    public function __construct(
        public string $name,
        public string $type,
        public bool $required,
        public string|null $description = null,
    ) {
    }
}
