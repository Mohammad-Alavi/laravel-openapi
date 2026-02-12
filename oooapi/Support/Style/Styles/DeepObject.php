<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Styles;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\Style\Base;

/**
 * DeepObject style serialization for nested objects.
 *
 * Applicable locations: query only
 * Default explode: true (always uses explode behavior)
 *
 * Serialization behavior:
 * - Only supports objects (not primitives or arrays)
 * - Renders nested objects as: name[key1]=value1&name[key2]=value2
 * - Example: color[R]=100&color[G]=200&color[B]=150
 *
 * Note: Behavior for nested objects and arrays within the object is undefined
 * in the specification.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#style-values
 */
final class DeepObject extends Base
{
    protected function value(): string
    {
        return 'deepObject';
    }
}
