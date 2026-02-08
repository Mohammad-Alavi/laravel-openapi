<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Serialization;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\MergeableFields;

/**
 * Serialization rule for Parameter Object.
 *
 * Defines how parameter values are serialized. Fields from implementing
 * classes (schema, style, explode, etc.) are merged into the parent
 * Parameter object at the same level as name and in.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#parameter-object
 */
interface SerializationRule extends MergeableFields
{
}
