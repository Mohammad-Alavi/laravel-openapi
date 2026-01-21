<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface;

/**
 * Marks objects whose fields should be merged into their parent's array representation.
 *
 * In OpenAPI, some objects have fields that appear at the same level as their
 * parent's fields rather than being nested. For example:
 *
 * - PathItem: HTTP method operations (get, post, put) appear as direct keys
 * - Parameter: SerializationRule fields (schema, style) appear alongside name, in
 *
 * Objects implementing this interface signal that their serialized fields
 * should be spread into the parent object using the spread operator.
 *
 * @see https://spec.openapis.org/oas/v3.2.0#path-item-object
 * @see https://spec.openapis.org/oas/v3.2.0#parameter-object
 */
interface MergeableFields extends \JsonSerializable
{
    /**
     * Returns an associative array of fields to be merged into the parent object.
     * Returns null when there are no fields to merge.
     *
     * @return array<string, mixed>|null
     */
    public function jsonSerialize(): array|null;
}
