<?php

namespace MohammadAlavi\LaravelOpenApi\Objects;

use Illuminate\Routing\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MohammadAlavi\LaravelOpenApi\Attributes\Parameter;

class RouteInformation
{
    public string|null $domain;
    public string $method;
    public string $uri;
    public string|null $name;
    /** @var string|class-string<Controller> */
    public string $controller;
    public Collection $parameters;
    /** @var Collection|\Attribute[] */
    public Collection|array $controllerAttributes;
    public string $action;
    /** @var \ReflectionParameter[] */
    public array $actionParameters;
    public Collection $actionAttributes;

    /**
     * @throws \ReflectionException
     */
    public static function createFromRoute(Route $route): RouteInformation
    {
        return tap(new static(), static function (self $instance) use ($route): void {
            $method = collect($route->methods())
                ->map(static fn ($value) => Str::lower($value))
                ->filter(static fn ($value) => !in_array($value, ['head', 'options'], true))
                ->first();

            $actionNameParts = explode('@', $route->getActionName());

            if (2 === count($actionNameParts)) {
                [$controller, $action] = $actionNameParts;
            } else {
                [$controller] = $actionNameParts;
                $action = '__invoke';
            }

            preg_match_all('/{(.*?)}/', $route->uri, $parameters);
            $parameters = collect($parameters[1]);

            if (count($parameters) > 0) {
                $parameters = $parameters->map(static fn ($parameter) => [
                    'name' => Str::replaceLast('?', '', $parameter),
                    'required' => !Str::endsWith($parameter, '?'),
                ]);
            }

            $reflectionClass = new \ReflectionClass($controller);
            $reflectionMethod = $reflectionClass->getMethod($action);

            $controllerAttributes = collect($reflectionClass->getAttributes())
                ->map(static fn (\ReflectionAttribute $attribute) => $attribute->newInstance());

            $actionAttributes = collect($reflectionMethod->getAttributes())
                ->map(static fn (\ReflectionAttribute $attribute) => $attribute->newInstance());

            $containsControllerLevelParameter = $actionAttributes->contains(static fn ($value) => $value instanceof Parameter);

            $instance->domain = $route->domain();
            $instance->method = $method;
            $instance->uri = Str::start($route->uri(), '/');
            $instance->name = $route->getName();
            $instance->controller = $controller;
            $instance->parameters = $containsControllerLevelParameter ? collect([]) : $parameters;
            $instance->controllerAttributes = $controllerAttributes;
            $instance->action = $action;
            $instance->actionParameters = $reflectionMethod->getParameters();
            $instance->actionAttributes = $actionAttributes;
        });
    }
}
