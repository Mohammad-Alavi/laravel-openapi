<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema;

final readonly class RequestStrategy
{
    public function __construct(
        public RequestDetector $detector,
        public RequestSchemaBuilder $builder,
    ) {
    }
}
