<?php

declare(strict_types=1);

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Base;

/**
 * Cookie style serialization (RFC6265).
 *
 * Applicable locations: cookie
 * Default explode: true
 *
 * Follows RFC6265 cookie syntax rules without percent-encoding.
 * Unlike form style, this uses the native cookie format.
 *
 * Serialization behavior:
 * - primitive: value as-is
 * - array: comma-separated values
 * - object: comma-separated key=value pairs
 *
 * @see https://spec.openapis.org/oas/v3.2.0#style-values
 * @see https://www.rfc-editor.org/rfc/rfc6265
 */
final class Cookie extends Base
{
    protected function value(): string
    {
        return 'cookie';
    }
}
