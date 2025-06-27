<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Response;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\ResponseExpression;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Sources\HeaderReference;

/**
 * Represents a response header runtime expression ($response.header.{token}).
 */
final readonly class ResponseHeaderExpression extends ResponseExpression
{
    private function __construct(
        private HeaderReference $headerReference,
    ) {
        parent::__construct(ResponseExpression::PREFIX . $headerReference->toString());
    }

    /**
     * Create a new response header expression.
     */
    public static function create(string $value): self
    {
        // If the value is already a full expression, extract the token
        if (str_starts_with($value, ResponseExpression::PREFIX . HeaderReference::PREFIX)) {
            $token = substr($value, strlen(ResponseExpression::PREFIX . HeaderReference::PREFIX));

            return new self(HeaderReference::create($token));
        }

        // Otherwise, assume the value is just the token
        return new self(HeaderReference::create($value));
    }

    /**
     * Get the header reference.
     */
    public function headerReference(): HeaderReference
    {
        return $this->headerReference;
    }

    /**
     * Get the header token.
     */
    public function token(): string
    {
        return $this->headerReference->token();
    }
}
