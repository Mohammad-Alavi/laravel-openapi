<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

interface ResponseSchemaBuilder
{
    /**
     * Build a JSON Schema from a detected response class.
     *
     * @param class-string $responseClass
     */
    public function build(string $responseClass): JSONSchema;
}
