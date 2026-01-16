<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Contracts;

/**
 * Base contract for JSON Schema keywords.
 * Dialect-agnostic - can be implemented by any JSON Schema version.
 *
 * @see https://json-schema.org/learn/glossary#keyword
 */
interface Keyword extends \JsonSerializable
{
    /**
     * The keyword name as it appears in JSON (e.g., "type", "maximum", "$ref").
     */
    public static function name(): string;

    /**
     * The keyword's value.
     */
    public function value(): mixed;
}
