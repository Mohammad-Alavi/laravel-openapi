<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RequestSchema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ObjectRestrictor;

final readonly class RequestSchemaResult
{
    public function __construct(
        public ObjectRestrictor $schema,
        public RequestTarget $target,
        public ContentEncoding $encoding = ContentEncoding::JSON,
    ) {
    }
}
