<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema;

interface ResponseDetector
{
    /**
     * Detect if a controller method returns a response class handled by this strategy.
     *
     * @param class-string $controllerClass
     *
     * @return class-string|null The detected response class, or null if not applicable
     */
    public function detect(string $controllerClass, string $method): string|null;
}
