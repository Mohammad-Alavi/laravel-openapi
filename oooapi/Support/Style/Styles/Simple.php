<?php

declare(strict_types=1);

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Base;

/**
 * Simple style serialization (RFC6570).
 *
 * Applicable locations: path, header
 * Default explode: false
 *
 * Serialization behavior:
 * - primitive: value as-is
 * - array: comma-separated values (e.g., "blue,black,brown")
 * - object: comma-separated key=value pairs (e.g., "R,100,G,200,B,150")
 *
 * With explode=true:
 * - array: comma-separated values (same as explode=false for simple)
 * - object: comma-separated key=value pairs (e.g., "R=100,G=200,B=150")
 *
 * @see https://spec.openapis.org/oas/v3.1.1#style-values
 */
final class Simple extends Base
{
    protected function value(): string
    {
        return 'simple';
    }
}
