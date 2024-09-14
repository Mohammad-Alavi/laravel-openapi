<?php

namespace MohammadAlavi\LaravelOpenApi\Collectors;

use MohammadAlavi\LaravelOpenApi\Collectors\Components\CallbackCollector;
use MohammadAlavi\LaravelOpenApi\Collectors\Components\RequestBodyCollector;
use MohammadAlavi\LaravelOpenApi\Collectors\Components\ResponseCollector;
use MohammadAlavi\LaravelOpenApi\Collectors\Components\SchemaCollector;
use MohammadAlavi\LaravelOpenApi\Collectors\Components\SecuritySchemeCollector;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\ObjectOrientedOAS\Objects\Components;

final readonly class ComponentCollector
{
    public function __construct(
        private CallbackCollector $callbackCollector,
        private RequestBodyCollector $requestBodyCollector,
        private ResponseCollector $responseCollector,
        private SchemaCollector $schemaCollector,
        private SecuritySchemeCollector $securitySchemeCollector,
    ) {
    }

    public function collect(string|null $collection = null, array $middlewares = []): Components|null
    {
        $collection ??= Generator::COLLECTION_DEFAULT;
        $callbacks = $this->callbackCollector->collect($collection);
        $requestBodies = $this->requestBodyCollector->collect($collection);
        $responses = $this->responseCollector->collect($collection);
        $schemas = $this->schemaCollector->collect($collection);
        $securitySchemes = $this->securitySchemeCollector->collect($collection);

        $components = Components::create();

        $hasAnyObjects = false;

        if (count($callbacks) > 0) {
            $hasAnyObjects = true;

            $components = $components->callbacks(...$callbacks);
        }

        if (count($requestBodies) > 0) {
            $hasAnyObjects = true;

            $components = $components->requestBodies(...$requestBodies);
        }

        if (count($responses) > 0) {
            $hasAnyObjects = true;
            $components = $components->responses(...$responses);
        }

        if (count($schemas) > 0) {
            $hasAnyObjects = true;
            $components = $components->schemas(...$schemas);
        }

        if (count($securitySchemes) > 0) {
            $hasAnyObjects = true;
            $components = $components->securitySchemes(...$securitySchemes);
        }

        if (!$hasAnyObjects) {
            return null;
        }

        foreach ($middlewares as $middleware) {
            app($middleware)->after($components);
        }

        return $components;
    }
}
