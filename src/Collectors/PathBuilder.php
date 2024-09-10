<?php

namespace MohammadAlavi\LaravelOpenApi\Collectors;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes;
use MohammadAlavi\LaravelOpenApi\Attributes\Collection as CollectionAttribute;
use MohammadAlavi\LaravelOpenApi\Collectors\Paths\OperationBuilder;
use MohammadAlavi\LaravelOpenApi\Contracts\PathMiddleware;
use MohammadAlavi\LaravelOpenApi\Generator;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInformation;
use MohammadAlavi\ObjectOrientedOAS\Exceptions\InvalidArgumentException;
use MohammadAlavi\ObjectOrientedOAS\Objects\PathItem;

class PathBuilder
{
    private OperationBuilder $operationBuilder;

    public function __construct(
        OperationBuilder $operationBuilder,
    ) {
        $this->operationBuilder = $operationBuilder;
    }

    /**
     * @param PathMiddleware[] $middlewares
     *
     * @throws InvalidArgumentException
     */
    public function build(
        string $collection,
        array $middlewares,
    ): array {
        return $this->routes()
            ->filter(static function (RouteInformation $routeInformation) use ($collection) {
                // TODO: use these docs to refactor and simplify this code
                /** @var CollectionAttribute|null $collectionAttribute */
                // You can set the collection either on controller or per action (on each method)
                // the controller collection always overrides the action collection
                $collectionAttribute = collect()
                    ->merge($routeInformation->controllerAttributes)
                    ->merge($routeInformation->actionAttributes)
                    ->first(static fn (object $item) => $item instanceof CollectionAttribute);
                // $collectionAttribute is the list of collections that this controller or action belongs to
                // $collectionAttribute are collection attributes added to [#Collection] on the controller or action
                // $collectionAttribute->name is an array of strings, each string is a collection name (e.g. 'public', 'private', 'default')
                // $collectionAttribute->name is the name of the collections that this controller or action belongs to.
                // Maybe 'names' or 'collectionNames' was a more suitable name for this property.

                // $collection comes in from documentation config types
                // It is the name of the current collection being built

                // if there is no collection attribute on the controller or action, then $collectionAttribute will be null
                //                if (!$collectionAttribute) {
                //                    dump('dead!', $collectionAttribute);
                //                }

                return
                    (!$collectionAttribute && Generator::COLLECTION_DEFAULT === $collection)
                    || ($collectionAttribute && in_array($collection, $collectionAttribute->name, true));
            })
            ->map(static function (RouteInformation $item) use ($middlewares) {
                foreach ($middlewares as $middleware) {
                    app($middleware)->before($item);
                }

                return $item;
            })
            ->groupBy(static fn (RouteInformation $routeInformation) => $routeInformation->uri)
            ->map(function (Collection $routes, $uri) {
                $pathItem = PathItem::create()->route($uri);

                $operations = $this->operationBuilder->build($routes);

                return $pathItem->operations(...$operations);
            })
            ->map(static function (PathItem $item) use ($middlewares) {
                foreach ($middlewares as $middleware) {
                    $item = app($middleware)->after($item);
                }

                return $item;
            })
            ->values()
            ->toArray();
    }

    protected function routes(): Collection
    {
        return collect(app(Router::class)->getRoutes())
            ->filter(static fn (Route $route) => 'Closure' !== $route->getActionName())
            ->map(static fn (Route $route) => RouteInformation::createFromRoute($route))
            ->filter(static function (RouteInformation $route) {
                $pathItem = $route->controllerAttributes
                    ->first(static fn (object $attribute) => $attribute instanceof Attributes\PathItem);

                $operation = $route->actionAttributes
                    ->first(static fn (object $attribute) => $attribute instanceof Attributes\Operation);

                return $pathItem && $operation;
            });
    }
}
