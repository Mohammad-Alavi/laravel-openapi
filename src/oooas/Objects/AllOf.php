<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

class AllOf extends SchemaComposition
{
    /**
     * @return string
     */
    protected function compositionType(): string
    {
        return 'allOf';
    }
}
