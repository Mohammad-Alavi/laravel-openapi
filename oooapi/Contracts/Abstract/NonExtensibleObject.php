<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract;

/**
 * Base class for OpenAPI objects that do NOT support specification extensions.
 *
 * Used for objects that are defined in the OpenAPI specification but
 * cannot be extended with custom "x-" prefixed properties.
 *
 * @see https://spec.openapis.org/oas/v3.1.0
 */
abstract class NonExtensibleObject extends Generatable
{
}
