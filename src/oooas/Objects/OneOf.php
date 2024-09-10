<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

class OneOf extends SchemaComposition
{
    protected function compositionType(): string
    {
        return 'oneOf';
    }
}
