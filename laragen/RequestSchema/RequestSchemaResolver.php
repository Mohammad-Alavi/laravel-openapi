<?php

namespace MohammadAlavi\Laragen\RequestSchema;

use Illuminate\Routing\Route;

final readonly class RequestSchemaResolver
{
    /** @param RequestStrategy[] $strategies */
    public function __construct(
        private array $strategies,
    ) {
    }

    /**
     * @param class-string $controllerClass
     */
    public function resolve(Route $route, string $controllerClass, string $method): RequestSchemaResult|null
    {
        foreach ($this->strategies as $strategy) {
            $detected = $strategy->detector->detect($route, $controllerClass, $method);

            if (null !== $detected) {
                return $strategy->builder->build($detected, $route);
            }
        }

        return null;
    }
}
