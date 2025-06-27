<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Response;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\ResponseExpression;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Sources\BodyReference;

/**
 * Represents a response body runtime expression ($response.body[#{json-pointer}]).
 */
final readonly class ResponseBodyExpression extends ResponseExpression
{
    private function __construct(
        private BodyReference $bodyReference,
    ) {
        parent::__construct(ResponseExpression::PREFIX . $bodyReference->toString());
    }

    /**
     * Create a new response body expression.
     */
    public static function create(string $value = ''): self
    {
        // If the value is already a full expression, extract the JSON pointer
        if (str_starts_with($value, ResponseExpression::PREFIX . BodyReference::PREFIX)) {
            $remaining = substr($value, strlen(ResponseExpression::PREFIX . BodyReference::PREFIX));

            // Check if there's a JSON pointer
            if ('' === $remaining || '0' === $remaining) {
                return new self(BodyReference::create());
            }

            // Must start with the pointer prefix
            if (!str_starts_with($remaining, BodyReference::POINTER_PREFIX)) {
                throw new \InvalidArgumentException(sprintf('Body reference JSON pointer must start with "%s", got "%s"', BodyReference::POINTER_PREFIX, $remaining));
            }

            $jsonPointer = substr($remaining, strlen(BodyReference::POINTER_PREFIX));

            return new self(BodyReference::create($jsonPointer));
        }

        // Otherwise, assume the value is just the JSON pointer (without the # prefix)
        return new self(BodyReference::create($value));
    }

    /**
     * Get the body reference.
     */
    public function bodyReference(): BodyReference
    {
        return $this->bodyReference;
    }

    /**
     * Get the JSON pointer.
     */
    public function jsonPointer(): string
    {
        return $this->bodyReference->jsonPointer();
    }
}
