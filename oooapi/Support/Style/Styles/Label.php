<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Explodable;

/**
 * Label style serialization (RFC6570 label expansion).
 *
 * Applicable locations: path only
 * Default explode: false
 *
 * Serialization behavior:
 * - primitive: .value (e.g., ".blue")
 * - array with explode=false: .value1,value2 (e.g., ".blue,black,brown")
 * - array with explode=true: .value1.value2 (e.g., ".blue.black.brown")
 * - object with explode=false: .key1,value1,key2,value2
 * - object with explode=true: .key1=value1.key2=value2
 *
 * @see https://spec.openapis.org/oas/v3.2.0#style-values
 */
final class Label extends Explodable
{
    protected function value(): string
    {
        return 'label';
    }
}
