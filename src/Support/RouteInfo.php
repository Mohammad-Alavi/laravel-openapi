<?php

namespace MohammadAlavi\LaravelOpenApi\Support;

use Illuminate\Routing\Controller;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteAction;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MohammadAlavi\LaravelOpenApi\Attributes\Callback;
use MohammadAlavi\LaravelOpenApi\Attributes\Extension;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use Webmozart\Assert\Assert;

class RouteInfo
{
    /** @var Collection<int, \Attribute>|null */
    public Collection|null $actionAttributes = null;

    private string|null $domain = null;
    private string|null $method = null;
    private string $uri;
    private string|null $name = null;

    /** @var string|class-string<Controller> */
    private string $controller = 'Closure';

    /** @var Collection<int, \Attribute>|null */
    private Collection|null $controllerAttributes = null;

    private string $action = 'Closure';

    /** @var \ReflectionParameter[] */
    private array $actionParameters = [];

    public static function create(Route $route): static
    {
        $method = collect($route->methods())
            ->map(static fn (string $value) => Str::lower($value))
            ->filter(static fn (string $value): bool => !in_array($value, ['head', 'options'], true))
            ->first();

        Assert::notNull(
            $method,
            'Unsupported HTTP method [' . implode(', ', $route->methods()) . '] for route: ' . $route->uri(),
        );

        return tap(new static(), static function (self $instance) use ($route, $method): void {
            if (static::isControllerAction($route)) {
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

            $instance->actionAttributes = collect();
            if ('Closure' !== $instance->controller) {
                $reflectionClass = new \ReflectionClass($instance->controller);
                $reflectionMethod = $reflectionClass->getMethod($instance->action);
                $instance->actionParameters = $reflectionMethod->getParameters();

                $controllerAttributes = collect($reflectionClass->getAttributes())
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
            $instance->controllerAttributes = $controllerAttributes ?? collect();
        });
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
        return is_string($route->action['uses']) && !static::isSerializedClosure($route);
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
            ->filter(static fn (object $attribute): bool => $attribute instanceof Extension);
    }

    public function actionAttributes(): Collection
    {
        return $this->actionAttributes ?? collect();
    }

    public function callbackAttributes(): Collection
    {
        return $this->actionAttributes()
            ->filter(static fn (object $attribute): bool => $attribute instanceof Callback);
    }

    public function pathItemAttribute(): PathItem|null
    {
        return $this->controllerAttributes()
            ->first(static fn (object $attribute): bool => $attribute instanceof PathItem);
    }

    public function controllerAttributes(): Collection
    {
        return $this->controllerAttributes ?? collect();
    }

    public function operationAttribute(): Operation|null
    {
        return $this->actionAttributes()
            ->first(static fn (object $attribute): bool => $attribute instanceof Operation);
    }
}
