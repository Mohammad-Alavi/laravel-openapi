<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Request;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\RequestExpression;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Sources\QueryReference;

/**
 * Represents a request query runtime expression ($request.query.{name}).
 */
final readonly class RequestQueryExpression extends RequestExpression
{
    private function __construct(
        private QueryReference $queryReference,
    ) {
        parent::__construct(RequestExpression::PREFIX . $queryReference->toString());
    }

    /**
     * Create a new request query expression.
     */
    public static function create(string $value): static
    {
        // If the value is already a full expression, extract the name
        if (str_starts_with($value, RequestExpression::PREFIX . QueryReference::PREFIX)) {
            $name = substr($value, strlen(RequestExpression::PREFIX . QueryReference::PREFIX));

            return new self(QueryReference::create($name));
        }

        // Otherwise, assume the value is just the name
        return new self(QueryReference::create($value));
    }

    /**
     * Get the query reference.
     */
    public function queryReference(): QueryReference
    {
        return $this->queryReference;
    }

    /**
     * Get the query name.
     */
    public function name(): string
    {
        return $this->queryReference->name();
    }
}
