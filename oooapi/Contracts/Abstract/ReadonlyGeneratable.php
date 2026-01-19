<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\OASObject;

/**
 * Base class for immutable generatable OpenAPI objects.
 *
 * Similar to Generatable but enforces readonly semantics for
 * objects that should be immutable once created.
 *
 * @see https://spec.openapis.org/oas/v3.1.0
 */
abstract readonly class ReadonlyGeneratable implements OASObject, \JsonSerializable
{
    use Generator;
}
