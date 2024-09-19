<?php

namespace MohammadAlavi\LaravelOpenApi\Concerns;

use MohammadAlavi\LaravelOpenApi\Contracts\Reusable;
use MohammadAlavi\LaravelOpenApi\Factories\Component\CallbackFactory;
use MohammadAlavi\LaravelOpenApi\Factories\Component\ParameterFactory;
use MohammadAlavi\LaravelOpenApi\Factories\Component\RequestBodyFactory;
use MohammadAlavi\LaravelOpenApi\Factories\Component\ResponseFactory;
use MohammadAlavi\LaravelOpenApi\Factories\Component\SchemaFactory;
use MohammadAlavi\LaravelOpenApi\Factories\Component\SecuritySchemeFactory;
use MohammadAlavi\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use MohammadAlavi\ObjectOrientedOAS\Objects\Schema;

// TODO: cleanup this.
// Is it even used? How?
// It seems there is duplicate functionality spread over some other classes like:
// MohammadAlavi\LaravelOpenApi\Collectors\Paths\Operations\RequestBodyBuilder
// MohammadAlavi\LaravelOpenApi\Collectors\Paths\Operations\ResponseBuilder
// MohammadAlavi\LaravelOpenApi\Collectors\Paths\Operations\CallbackBuilder
trait Referencable
{
    public static function ref(string|null $objectId = null): Schema
    {
        $instance = app(static::class);

        if (!$instance instanceof Reusable) {
            throw new InvalidArgumentException('"' . static::class . '" must implement "' . Reusable::class . '" in order to be referencable.');
        }

        $baseRef = null;

        if ($instance instanceof CallbackFactory) {
            $baseRef = '#/components/callbacks/';
        } elseif ($instance instanceof ParameterFactory) {
            $baseRef = '#/components/parameters/';
        } elseif ($instance instanceof RequestBodyFactory) {
            $baseRef = '#/components/requestBodies/';
        } elseif ($instance instanceof ResponseFactory) {
            $baseRef = '#/components/responses/';
        } elseif ($instance instanceof SchemaFactory) {
            $baseRef = '#/components/schemas/';
        } elseif ($instance instanceof SecuritySchemeFactory) {
            $baseRef = '#/components/securitySchemes/';
        }

        return Schema::ref($baseRef . $instance->build()->objectId, $objectId);
    }
}
