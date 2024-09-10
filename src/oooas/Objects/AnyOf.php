<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

class AnyOf extends SchemaComposition
{
    protected function compositionType(): string
    {
        return 'anyOf';
    }
}
