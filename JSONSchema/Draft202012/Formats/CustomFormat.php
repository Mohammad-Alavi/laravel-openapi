<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats;

/**
 * Simple value object for custom/ad-hoc format strings.
 *
 * Use this when you need a one-off format that isn't in the standard StringFormat enum.
 *
 * Usage:
 *   ->format(CustomFormat::create('phone-number'))
 *   ->format(CustomFormat::create('credit-card'))
 *   ->format('my-format') // Shorthand - automatically creates CustomFormat
 *
 * @see https://json-schema.org/understanding-json-schema/reference/string#format
 */
final readonly class CustomFormat implements DefinedFormat
{
    private function __construct(
        private string $format,
    ) {
    }

    /**
     * Create a custom format from a string.
     */
    public static function create(string $format): self
    {
        return new self($format);
    }

    public function value(): string
    {
        return $this->format;
    }
}
