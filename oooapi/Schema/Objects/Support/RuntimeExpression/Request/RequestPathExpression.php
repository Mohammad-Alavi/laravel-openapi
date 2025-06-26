<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Request;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\RequestExpression;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Support\RuntimeExpression\Sources\PathReference;

/**
 * Represents a request path runtime expression ($request.path.{name}).
 */
final readonly class RequestPathExpression extends RequestExpression
{
    private function __construct(
        private PathReference $pathReference,
    ) {
        parent::__construct(RequestExpression::PREFIX . $pathReference->toString());
    }

    /**
     * Create a new request path expression.
     */
    public static function create(string $value): static
    {
        // If the value is already a full expression, extract the name
        if (str_starts_with($value, RequestExpression::PREFIX . PathReference::PREFIX)) {
            $name = substr($value, strlen(RequestExpression::PREFIX . PathReference::PREFIX));

            return new self(PathReference::create($name));
        }

        // Otherwise, assume the value is just the name
        return new self(PathReference::create($value));
    }

    /**
     * Get the path reference.
     */
    public function pathReference(): PathReference
    {
        return $this->pathReference;
    }

    /**
     * Get the path name.
     */
    public function name(): string
    {
        return $this->pathReference->name();
    }
}
