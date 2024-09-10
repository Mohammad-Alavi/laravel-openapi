<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

class AllOf extends SchemaComposition
{
    protected function compositionType(): string
    {
        return 'allOf';
    }
}
