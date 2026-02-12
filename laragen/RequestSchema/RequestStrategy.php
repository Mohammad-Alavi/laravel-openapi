<?php

namespace MohammadAlavi\Laragen\RequestSchema;

final readonly class RequestStrategy
{
    public function __construct(
        public RequestDetector $detector,
        public RequestSchemaBuilder $builder,
    ) {
    }
}
