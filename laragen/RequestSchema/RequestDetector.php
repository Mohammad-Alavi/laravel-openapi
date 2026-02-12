<?php

namespace MohammadAlavi\Laragen\RequestSchema;

use Illuminate\Routing\Route;

interface RequestDetector
{
    /**
     * Detect if a route has request schema information handled by this strategy.
     *
     * @param class-string $controllerClass
     *
     * @return mixed|null The detected context (e.g., rules array, class name), or null if not applicable
     */
    public function detect(Route $route, string $controllerClass, string $method): mixed;
}
