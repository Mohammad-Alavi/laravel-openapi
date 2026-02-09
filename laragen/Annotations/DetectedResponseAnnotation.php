<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\Annotations;

final readonly class DetectedResponseAnnotation
{
    public function __construct(
        public int $status,
        public string $json,
    ) {
    }
}
