<?php

declare(strict_types=1);

namespace MohammadAlavi\Laragen\RouteDiscovery;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;

final readonly class AutoRouteCollector
{
    public function __construct(
        private Router $router,
    ) {
    }

    /**
     * Collect routes matching the given pattern matcher.
     *
     * @return Collection<int, RouteInfo>
     */
    public function collect(PatternMatcher $matcher): Collection
    {
        return collect($this->router->getRoutes()->getRoutes())
            ->filter(static fn (Route $route): bool => 'Closure' !== $route->getActionName())
            ->filter(static fn (Route $route): bool => $matcher->matches($route->uri()))
            ->map(static fn (Route $route): RouteInfo => RouteInfo::create($route))
            ->values();
    }
}
