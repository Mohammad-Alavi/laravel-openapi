<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema;

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;

final readonly class ResponseSchemaResolver
{
    /** @param ResponseStrategy[] $strategies */
    public function __construct(
        private array $strategies,
    ) {
    }

    /**
     * @param class-string $controllerClass
     */
    public function resolve(string $controllerClass, string $method): JSONSchema|null
    {
        foreach ($this->strategies as $strategy) {
            $detected = $strategy->detector->detect($controllerClass, $method);

            if (null !== $detected) {
                return $strategy->builder->build($detected);
            }
        }

        return null;
    }
}
