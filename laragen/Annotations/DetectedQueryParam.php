<?php

namespace MohammadAlavi\Laragen\Annotations;

final readonly class DetectedQueryParam
{
    public function __construct(
        public string $name,
        public string $type,
        public string|null $description = null,
    ) {
    }
}
