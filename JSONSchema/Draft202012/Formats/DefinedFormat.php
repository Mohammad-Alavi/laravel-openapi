<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats;

/**
 * Contract for format values.
 *
 * Implement this interface to create your own format enums or classes.
 * This allows for type-safe format values while supporting custom formats.
 *
 * Built-in implementations:
 * - StringFormat: Enum with all JSON Schema standard formats
 * - CustomFormat: Simple value object for ad-hoc format strings
 *
 * Example custom enum:
 * ```php
 * enum MyFormats: string implements DefinedFormat
 * {
 *     case PHONE = 'phone-number';
 *     case CREDIT_CARD = 'credit-card';
 *     case SSN = 'ssn';
 *
 *     public function value(): string
 *     {
 *         return $this->value;
 *     }
 * }
 *
 * // Usage:
 * StrictFluentDescriptor::string()->format(MyFormats::PHONE);
 * ```
 *
 * @see https://json-schema.org/understanding-json-schema/reference/string#format
 */
interface DefinedFormat
{
    /**
     * Get the format string value.
     */
    public function value(): string;
}
