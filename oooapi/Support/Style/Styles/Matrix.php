<?php

declare(strict_types=1);

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Base;

/**
 * Matrix style serialization (RFC6570 path-style expansion).
 *
 * Applicable locations: path only
 * Default explode: false
 *
 * Serialization behavior:
 * - primitive: ;name=value (e.g., ";color=blue")
 * - array with explode=false: ;name=value1,value2 (e.g., ";color=blue,black,brown")
 * - array with explode=true: ;name=value1;name=value2 (e.g., ";color=blue;color=black")
 * - object with explode=false: ;name=key1,value1,key2,value2
 * - object with explode=true: ;key1=value1;key2=value2
 *
 * @see https://spec.openapis.org/oas/v3.1.1#style-values
 */
final class Matrix extends Base
{
    protected function value(): string
    {
        return 'matrix';
    }
}
