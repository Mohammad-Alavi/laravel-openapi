<?php

namespace MohammadAlavi\Laragen\RequestSchema;

use Illuminate\Routing\Route;

interface RequestSchemaBuilder
{
    /**
     * Build a request schema result from detected context.
     */
    public function build(mixed $detected, Route $route): RequestSchemaResult;
}
