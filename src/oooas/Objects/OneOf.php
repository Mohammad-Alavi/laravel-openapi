<?php

namespace MohammadAlavi\ObjectOrientedOAS\Objects;

class OneOf extends SchemaComposition
{
    /**
     * @return string
     */
    protected function compositionType(): string
    {
        return 'oneOf';
    }
}
