<?php

namespace MohammadAlavi\LaravelOpenApi\Support;

use Illuminate\Routing\Controller;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteAction;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MohammadAlavi\LaravelOpenApi\Attributes\Extension;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Webmozart\Assert\Assert;

final class RouteInfo
{
    private string|null $domain = null;
    private string|null $method = null;
    private string $uri;
    private string|null $name = null;

    /** @var string|class-string<Controller> */
    private string $controller = 'Closure';

    /** @var Collection<int, object> */
    private Collection $controllerAttributes;

    /** @var Collection<int, object> */
    private Collection $actionAttributes;

    private string $action = 'Closure';

    /** @var \ReflectionParameter[] */
    private array $actionParameters = [];

    private CollectionMatcher|null $collectionMatcher = null;

    private function __construct()
    {
        $this->controllerAttributes = collect();
        $this->actionAttributes = collect();
    }

    public static function create(Route $route): self
    {
        $method = collect($route->methods())
            ->map(static fn (string $value) => Str::lower($value))
            ->filter(static fn (string $value): bool => !in_array($value, ['head', 'options'], true))
            ->first();

        Assert::notNull(
            $method,
            'Unsupported HTTP method [' . implode(', ', $route->methods()) . '] for route: ' . $route->uri(),
        );

        return tap(new self(), static function (self $instance) use ($route, $method): void {
            if (self::isControllerAction($route)) {
                [$controller, $action] = Str::parseCallback($route->getAction('uses'));
                $instance->action = $action;
                $instance->controller = $controller;
            } elseif (!$route->getAction('uses') instanceof \Closure) {
                $instance->controller = $route->getAction()[0];
                $instance->action = '__invoke';
            } else {
                $instance->controller = 'Closure';
                $instance->action = 'Closure';
            }

            if ('Closure' !== $instance->controller) {
                $reflectionClass = new \ReflectionClass($instance->controller);
                $reflectionMethod = $reflectionClass->getMethod($instance->action);
                $instance->actionParameters = $reflectionMethod->getParameters();

                $instance->controllerAttributes = collect($reflectionClass->getAttributes())
                    ->map(
                        static fn (
                            \ReflectionAttribute $reflectionAttribute,
                        ): object => $reflectionAttribute->newInstance(),
                    );

                $instance->actionAttributes = collect($reflectionMethod->getAttributes())
                    ->map(
                        static fn (
                            \ReflectionAttribute $reflectionAttribute,
                        ): object => $reflectionAttribute->newInstance(),
                    );
            }

            $instance->domain = $route->domain();
            $instance->method = $method;
            $instance->uri = Str::start($route->uri(), '/');
            $instance->name = $route->getName();
        });
    }

    /**
     * Returns a new RouteInfo with the given action attributes.
     * This is primarily useful for testing scenarios.
     *
     * @param Collection<int, object> $attributes
     */
    public function withActionAttributes(Collection $attributes): self
    {
        $clone = clone $this;
        $clone->actionAttributes = $attributes;

        return $clone;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Checks whether the route's action is a controller.
     */
    private static function isControllerAction(Route $route): bool
    {
        return is_string($route->action['uses']) && !self::isSerializedClosure($route);
    }

    /**
     * Determine if the route action is a serialized Closure.
     */
    private static function isSerializedClosure(Route $route): bool
    {
        return RouteAction::containsSerializedClosure($route->action);
    }

    public function domain(): string|null
    {
        return $this->domain;
    }

    public function method(): string
    {
        return Str::lower($this->method);
    }

    public function controller(): string
    {
        return $this->controller;
    }

    public function action(): string
    {
        return $this->action;
    }

    public function name(): string|null
    {
        return $this->name;
    }

    public function actionParameters(): array
    {
        return $this->actionParameters;
    }

    public function extensionAttributes(): Collection
    {
        return $this->actionAttributes()
            ->filter(
                static function (object $attribute): bool {
                    return $attribute instanceof Extension;
                },
            );
    }

    public function actionAttributes(): Collection
    {
        return $this->actionAttributes;
    }

    public function pathItemAttribute(): PathItem|null
    {
        return $this->controllerAttributes()
            ->first(
                static function (object $attribute): bool {
                    return $attribute instanceof PathItem;
                },
            );
    }

    public function controllerAttributes(): Collection
    {
        return $this->controllerAttributes;
    }

    public function operationAttribute(): Operation|null
    {
        return $this->actionAttributes()
            ->first(
                static function (object $attribute): bool {
                    return $attribute instanceof Operation;
                },
            );
    }

    /**
     * Get the collection matcher for this route.
     *
     * Use this to check collection membership:
     * - $routeInfo->collection()->isInCollection('api')
     * - $routeInfo->collection()->hasCollectionAttribute()
     */
    public function collection(): CollectionMatcher
    {
        return $this->collectionMatcher ??= new CollectionMatcher($this);
    }
}
