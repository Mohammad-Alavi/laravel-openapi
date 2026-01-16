<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Schema\Style;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\MergeableFields;

/**
 * Style interface for parameter serialization styles.
 *
 * Defines how parameters are serialized. Fields from implementing classes
 * are merged into the parent SchemaSerialized object at the same level
 * as the schema field.
 *
 * @see https://spec.openapis.org/oas/v3.1.0#style-values
 */
interface Style extends MergeableFields
{
    public function toArray(): array;
}
