<?php

namespace MohammadAlavi\Laragen\Support;

enum Applicator: string
{
    case ALL_OF = 'allOf';
    case ANY_OF = 'anyOf';
    case ONE_OF = 'oneOf';

    public function toString(): string
    {
        return $this->value;
    }
}
