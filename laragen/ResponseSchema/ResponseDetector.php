<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\ResponseSchema;

interface ResponseDetector
{
    /**
     * Detect if a controller method returns a response handled by this strategy.
     *
     * @param class-string $controllerClass
     *
     * @return mixed The detected context (e.g., class name, parsed annotations), or null if not applicable
     */
    public function detect(string $controllerClass, string $method): mixed;
}
