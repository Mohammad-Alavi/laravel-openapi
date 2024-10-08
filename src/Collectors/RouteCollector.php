<?php

namespace MohammadAlavi\LaravelOpenApi\Collectors;

use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Contracts\RouteCollector as RouteCollectorContract;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInformation;

readonly class RouteCollector implements RouteCollectorContract
{
    public function __construct(
        private Router $router,
    ) {
    }

    /** @return Collection<int, RouteInformation> */
    public function getRoutes(): Collection
    {
        return collect($this->router->getRoutes())
            ->filter(static fn (Route $route): bool => 'Closure' !== $route->getActionName())
            ->map(static fn (Route $route): RouteInformation => RouteInformation::createFromRoute($route))
            ->filter(static function (RouteInformation $routeInformation): bool {
                $pathItem = $routeInformation->controllerAttributes
                    ->first(static fn (object $attribute): bool => $attribute instanceof PathItem);

                $operation = $routeInformation->actionAttributes
                    ->first(static fn (object $attribute): bool => $attribute instanceof Operation);

                return $pathItem && $operation;
            });
    }
}
