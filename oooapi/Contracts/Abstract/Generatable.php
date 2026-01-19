<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;

/**
 * Base class for all generatable OpenAPI objects.
 *
 * Provides the foundation for objects that can be serialized to JSON
 * as part of an OpenAPI specification document.
 *
 * @see https://spec.openapis.org/oas/v3.1.0
 */
abstract class Generatable implements OASObject, \JsonSerializable
{
    use Generator;
}
