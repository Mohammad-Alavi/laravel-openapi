<?php

declare(strict_types=1);

namespace MohammadAlavi\LaravelOpenApi\Support;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteSignatureParameters as RSP;
use Illuminate\Support\Arr;
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

        if (preg_match_all('/\{(\w+)\?}/', $route->uri(), $m)) {
            $optional = $m[1];
        }

        $refParams = collect(RSP::fromAction($route->getAction()))
            ->reject(
                function (\ReflectionParameter $parameter) {
                    return $parameter->isVariadic()
                        || $parameter->isDefaultValueAvailable()
                        || $parameter->isOptional()
                        || $this->isFormRequest($parameter);
                },
            );
        $byName = $refParams->keyBy(static fn (\ReflectionParameter $parameter) => $parameter->getName());

        // pool of model-bound parameters whose name does *not* match the uri segment
        $modelPool = $refParams->filter(
            static fn (\ReflectionParameter $parameter) => is_subclass_of(
                $parameter->getType()?->getName() ?? '',
                Model::class,
            ),
        )->values();

        return collect($uriParams)->mapWithKeys(
            function (string $name) use ($byName, &$modelPool, $route, $optional): array {
                // match by name, else first model
                if ($byName->get($name)) {
                    $p = $byName->get($name);
                } else {
                    $p = $modelPool->shift();
                }

                if ($p?->hasType()) {
                    $type = $this->normalizePhpType($p->getType()->getName());
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

    /** @return array<string,mixed> */
    public function bodyParams(Route $route): array
    {
        $requestParam = collect(
            RSP::fromAction($route->getAction(), ['subClass' => FormRequest::class]),
        )->first();

        if (!$requestParam) {
            return [];
        }

        /** @var FormRequest $request */
        $request = app($requestParam->getType()->getName());

        if (!method_exists($request, 'rules')) {
            return [];
        }

        $validator = validator([], $request->rules(), $request->messages(), $request->attributes());

        return collect($validator->getRules())
            ->map(fn ($rules) => $this->ruleSetToSchema(Arr::wrap($rules)))
            ->all();
    }

    /** @param array<int,string|ValidationRule> $ruleSet */
    public function ruleSetToSchema(array $ruleSet): array
    {
        /** @var Collection<array-key, string> $rules */
        $rules = collect($ruleSet)->map(
            static function ($rule): string {
                if (is_string($rule)) {
                    return $rule;
                }

                return class_basename($rule);
            },
        );

        $schema = match (true) {
            !is_null($rules->first(
                static function ($r) {
                    return str_contains($r, 'integer') || str_contains($r, 'numeric');
                },
            )) => ['type' => 'integer', 'example' => fake()->numberBetween(1, 1000)],
            $rules->contains('boolean') => ['type' => 'boolean', 'example' => fake()->boolean()],
            $rules->contains('array') => ['type' => 'array', 'example' => []],
            !is_null($rules->first(
                static function ($r) {
                    return str_contains($r, 'file') || str_contains($r, 'image') || str_contains($r, 'mimes');
                },
            )) => ['type' => 'string', 'format' => 'binary'],
            !is_null($rules->first(
                static function ($r) {
                    return str_contains($r, 'date');
                },
            )) => ['type' => 'string', 'format' => 'date', 'example' => fake()->date()],
            $rules->contains('email') => ['type' => 'string', 'format' => 'email', 'example' => fake()->email()],
            default => ['type' => 'string', 'example' => fake()->word()],
        };

        foreach ($rules as $rule) {
            if (preg_match('/^(min|max):(\d+)/', $rule, $m)) {
                $schema['min' === $m[1] ? 'minimum' : 'maximum'] = (int)$m[2];
            }

            if (preg_match('/^between:(\d+),(\d+)/', $rule, $m)) {
                $schema['minimum'] = (int)$m[1];
                $schema['maximum'] = (int)$m[2];
            }

            if (preg_match('/^in:(.+)$/', $rule, $m)) {
                $schema['enum'] = explode(',', $m[1]);
            }

            if (preg_match('/^regex:(.+)$/', $rule, $m)) {
                $schema['pattern'] = $m[1];
            }
        }

        return $schema;
    }
}
