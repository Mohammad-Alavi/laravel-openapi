<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema;

final readonly class ResponseStrategy
{
    public function __construct(
        public ResponseDetector $detector,
        public ResponseSchemaBuilder $builder,
    ) {
    }
}
