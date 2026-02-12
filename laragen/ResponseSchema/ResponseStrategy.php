<?php

namespace MohammadAlavi\Laragen\ResponseSchema;

final readonly class ResponseStrategy
{
    public function __construct(
        public ResponseDetector $detector,
        public ResponseSchemaBuilder $builder,
    ) {
    }
}
