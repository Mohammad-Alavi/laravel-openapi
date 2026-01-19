<?php

namespace MohammadAlavi\LaravelOpenApi\Support;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Collection as CollectionAlias;
use Webmozart\Assert\Assert;

final readonly class RouteCollector
{
    public function __construct(
        private Router $router,
    ) {
    }

    /**
     * Get all routes that should be collected for the given collection.
     *
     * @param non-empty-string $collection
     *
     * @return Collection<int, RouteInfo>
     */
    public function whereShouldBeCollectedFor(string $collection): Collection
    {
        Assert::stringNotEmpty($collection);

        return $this->all()->filter(
            function (RouteInfo $routeInfo) use ($collection): bool {
                if (config()->boolean('openapi.collection.default.include_routes_without_attribute', false)) {
                    return (!$routeInfo->hasCollectionAttribute() && $this->generatingDefaultCollection($collection))
                        || $routeInfo->isInCollection($collection);
                }

                return $routeInfo->isInCollection($collection);
            },
        );
    }

    /** @return Collection<int, RouteInfo> */
    public function all(): Collection
    {
        return collect($this->router->getRoutes())
            ->filter(
                static function (Route $route): bool {
                    return 'Closure' !== $route->getActionName();
                },
            )->map(
                static function (Route $route): RouteInfo {
                    return RouteInfo::create($route);
                },
            );
    }

    private function generatingDefaultCollection(string $collection): bool
    {
        return CollectionAlias::DEFAULT === $collection;
    }
}
