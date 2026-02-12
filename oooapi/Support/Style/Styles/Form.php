<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Explodable;

/**
 * Form style serialization (RFC6570).
 *
 * Applicable locations: query, cookie
 * Default explode: true (unlike other styles which default to false)
 *
 * Serialization behavior:
 * - primitive: name=value (e.g., "color=blue")
 * - array with explode=false: name=value1,value2 (e.g., "color=blue,black,brown")
 * - array with explode=true: name=value1&name=value2 (e.g., "color=blue&color=black")
 * - object with explode=false: name=key1,value1,key2,value2
 * - object with explode=true: key1=value1&key2=value2
 *
 * @see https://spec.openapis.org/oas/v3.2.0#style-values
 */
final class Form extends Explodable
{
    protected function value(): string
    {
        return 'form';
    }
}
