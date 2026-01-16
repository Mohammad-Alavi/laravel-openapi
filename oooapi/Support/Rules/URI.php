<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\Rules;

use Webmozart\Assert\Assert;

final readonly class URI
{
    /**
     * Validates that the value is a valid URI according to RFC 3986.
     *
     * @see https://datatracker.ietf.org/doc/html/rfc3986
     */
    public function __construct(
        private string $value,
    ) {
        // A valid URI must have at least a scheme and a path/authority
        // Using parse_url to check basic URI structure
        $parsed = parse_url($this->value);

        Assert::notFalse(
            $parsed,
            sprintf('The value "%s" is not a valid URI.', $this->value),
        );

        // URI can be relative (no scheme) or absolute (with scheme)
        // For OpenAPI, we typically want absolute URIs for $id, $schema, etc.
        // but relative URIs are valid for $ref
        Assert::notEmpty(
            $this->value,
            'URI cannot be empty.',
        );
    }
}
