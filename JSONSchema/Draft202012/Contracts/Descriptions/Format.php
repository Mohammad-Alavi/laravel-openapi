<?php

namespace MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Descriptions;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\DefinedFormat;

interface Format
{
    /**
     * Set the format annotation for semantic validation hints.
     *
     * Accepts either a DefinedFormat object (like StringFormat enum) or a raw string.
     *
     * @param DefinedFormat|string $format The format - StringFormat enum, CustomFormat, or string
     *
     * @see https://json-schema.org/understanding-json-schema/reference/string#format
     */
    public function format(DefinedFormat|string $format): static;
}
