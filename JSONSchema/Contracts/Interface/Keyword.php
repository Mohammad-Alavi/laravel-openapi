<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Contracts\Interface;

/**
 * Keyword is a property appearing within a schema object.
 *
 * @see https://json-schema.org/learn/glossary#keyword
 */
interface Keyword
{
    public static function name(): string;

    public function value(): mixed;
}
