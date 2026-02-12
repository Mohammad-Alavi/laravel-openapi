<?php

namespace MohammadAlavi\Laragen\ResponseSchema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

interface ResponseSchemaBuilder
{
    /**
     * Build a JSON Schema from detected response context.
     */
    public function build(mixed $detected): JSONSchema;
}
