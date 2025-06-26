<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Request;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\RequestExpression;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Sources\HeaderReference;

/**
 * Represents a request header runtime expression ($request.header.{token}).
 */
final readonly class RequestHeaderExpression extends RequestExpression
{
    private function __construct(
        private HeaderReference $headerReference,
    ) {
        parent::__construct(RequestExpression::PREFIX . $headerReference->toString());
    }

    /**
     * Create a new request header expression.
     */
    public static function create(string $value): static
    {
        // If the value is already a full expression, extract the token
        if (str_starts_with($value, RequestExpression::PREFIX . HeaderReference::PREFIX)) {
            $token = substr($value, strlen(RequestExpression::PREFIX . HeaderReference::PREFIX));

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
