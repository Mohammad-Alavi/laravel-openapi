<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Formats;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;

enum NumberFormat: string implements DefinedFormat
{
    case FLOAT = 'float';

    case DOUBLE = 'double';

    public function value(): string
    {
        return $this->value;
    }
}
