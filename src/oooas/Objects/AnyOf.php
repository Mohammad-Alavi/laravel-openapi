<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

class AnyOf extends SchemaComposition
{
    /**
     * @return string
     */
    protected function compositionType(): string
    {
        return 'anyOf';
    }
}
