<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Keyword;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\CustomFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;

/**
 * The "format" keyword provides semantic validation hints for string values.
 *
 * Accepts a DefinedFormat (e.g., StringFormat::EMAIL) or a custom format string.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation#section-7
 */
final readonly class Format implements Keyword
{
    private function __construct(
        private DefinedFormat $definedFormat,
    ) {
    }

    /**
     * Create a format keyword from a DefinedFormat or raw string.
     *
     * @param DefinedFormat|string $format The format - either a DefinedFormat instance or a string
     */
    public static function create(DefinedFormat|string $format): self
    {
        if (is_string($format)) {
            $format = CustomFormat::create($format);
        }

        return new self($format);
    }

    public static function name(): string
    {
        return 'format';
    }

    public function jsonSerialize(): string
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->definedFormat->value();
    }

    /**
     * Get the underlying DefinedFormat object.
     */
    public function format(): DefinedFormat
    {
        return $this->definedFormat;
    }
}
