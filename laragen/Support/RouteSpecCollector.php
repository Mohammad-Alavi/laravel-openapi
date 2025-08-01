<?php

namespace MohammadAlavi\Laragen\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteSignatureParameters;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route as RouteFacade;

final readonly class RouteSpecCollector
{
    /** @return Collection<int,array> */
    public function collect(): Collection
    {
        return collect(RouteFacade::getRoutes())->map(function (Route $route) {
            return [
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'path' => $this->pathParams($route),
                'body' => $this->bodyParams($route),
                'name' => $route->getName(),
                'middleware' => $route->middleware(),
            ];
        });
    }

    public function pathParams(Route $route): array
    {
        $uriParams = $route->parameterNames();
        $optional = [];

        if (preg_match_all('/\{(\w+)\?}/', $route->uri(), $match)) {
            $optional = $match[1];
        }

        $routeParams = collect(RouteSignatureParameters::fromAction($route->getAction()))
            ->reject(
                function (\ReflectionParameter $parameter) {
                    return $parameter->isVariadic()
                        || $parameter->isDefaultValueAvailable()
                        || $parameter->isOptional()
                        || $this->isFormRequest($parameter);
                },
            );

        /** @var Collection<string, \ReflectionParameter> $paramsByName */
        $paramsByName = $routeParams->keyBy(static fn (\ReflectionParameter $parameter) => $parameter->getName());

        return collect($uriParams)->mapWithKeys(
            function (string $name) use ($paramsByName, $route, $optional): array {
                $param = $paramsByName->get($name);
                if ($param?->hasType()) {
                    $type = $this->normalizePhpType($param->getType()?->getName());
                } else {
                    $type = $this->guessFromWhere($route, $name);
                }

                return [
                    $name => [
                        'type' => $type,
                        'required' => !in_array($name, $optional, true),
                    ],
                ];
            },
        )->all();
    }

    public function isFormRequest(\ReflectionParameter $parameter): bool
    {
        $type = $parameter->getType();

        return !is_null($type) && is_subclass_of($type->getName(), FormRequest::class);
    }

    public function normalizePhpType(string $type): string
    {
        if (is_subclass_of($type, Model::class)) {
            /** @var Model $model */
            $model = new $type();

            return match ($model->getKeyType()) {
                'int', 'integer' => 'integer',
                default => 'string',
            };
        }

        return match (strtolower($type)) {
            'int', 'integer' => 'integer',
            'bool', 'boolean' => 'boolean',
            'array' => 'array',
            default => 'string',
        };
    }

    public function guessFromWhere(Route $route, string $name): string
    {
        $pattern = $route->wheres[$name] ?? '';

        if ('[0-9]+' === $pattern || '\d+' === $pattern || preg_match("/^\d[\d+]*$/", $pattern)) {
            return 'integer';
        }

        return 'string';
    }

    /**
     * @return array<string,mixed>
     */
    public function bodyParams(Route $route): array
    {
        /** @var \ReflectionParameter|null $requestParam */
        $requestParam = collect(
            RouteSignatureParameters::fromAction($route->getAction(), ['subClass' => FormRequest::class]),
        )->first();

        if (is_null($requestParam)) {
            return [];
        }

        /** @var FormRequest $request */
        $request = new ($requestParam->getType()?->getName())();

        if (!method_exists($request, 'rules')) {
            return [];
        }

        $validator = validator([], $request->rules(), $request->messages(), $request->attributes());

        return JSONSchemaUtil::fromRequestRules($validator->getRules())->toArray()['properties'];
    }
}
