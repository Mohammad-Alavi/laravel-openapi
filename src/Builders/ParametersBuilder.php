<?php

namespace MohammadAlavi\LaravelOpenApi\Builders;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ParametersFactory;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\PathParameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;
use Webmozart\Assert\Assert;

final readonly class ParametersBuilder
{
    public function build(RouteInfo $routeInfo): Parameters
    {
        $pathParams = $this->buildPathParams($routeInfo);

        $attrParams = $routeInfo->operationAttribute()?->parameters;
        $operationParams = $attrParams ? $this->buildOperationParams($attrParams) : null;

        $pathParamsMap = $this->mapParams($pathParams);
        $operationParamsMap = $this->mapParams($operationParams);

        return Parameters::create(...array_values(array_merge($pathParamsMap, $operationParamsMap)));
    }

    private function buildPathParams(RouteInfo $routeInfo): Parameters|null
    {
        /** @var Collection $params */
        $params = $this->pathParameters($routeInfo->uri())
            ->map(
                function (array $parameter) use ($routeInfo): Parameter|null {
                    $schema = Schema::string();

                    /** @var \ReflectionParameter|null $reflectionParameter */
                    $reflectionParameter = collect($routeInfo->actionParameters())
                        ->first(
                            static fn (\ReflectionParameter $reflectionParameter): bool => $reflectionParameter
                                    ->name === $parameter['name'],
                        );

                    if ($reflectionParameter) {
                        // The reflected param has no type, so ignore (should be defined in a ParametersFactory instead)
                        if (is_null($reflectionParameter->getType())) {
                            return null;
                        }

                        $schema = $this->guessFromReflectionType(
                            $reflectionParameter->getType(),
                        );
                    }

                    $param = Parameter::path(
                        $parameter['name'],
                        PathParameter::create($schema),
                    );

                    if ($parameter['required']) {
                        return $param->required();
                    }

                    return $param;
                },
            );
        $params = $params->filter(
            static function (Parameter|ParameterFactory|null $parameter): bool {
                return !is_null($parameter);
            },
        );

        if ($params->isEmpty()) {
            return null;
        }

        return Parameters::create(...$params);
    }

    private function pathParameters(string $uri): Collection
    {
        preg_match_all('/{(.*?)}/', $uri, $pathParams);
        $pathParams = collect($pathParams[1]);

        if (count($pathParams) > 0) {
            $pathParams = $pathParams->map(
                static function (string $parameter): array {
                    return [
                        'name' => Str::replaceLast('?', '', $parameter),
                        'required' => !Str::endsWith($parameter, '?'),
                    ];
                },
            );
        }

        return $pathParams;
    }

    private function guessFromReflectionType(\ReflectionType $reflectionType): JSONSchema
    {
        return match ($reflectionType->getName()) {
            'int' => Schema::integer(),
            'bool' => Schema::boolean(),
            default => Schema::string(),
        };
    }

    /** @param class-string<ParametersFactory> $factory */
    private function buildOperationParams(string $factory): Parameters
    {
        Assert::isAOf($factory, ParametersFactory::class);

        return (new $factory())->build();
    }

    /**
     * @return array<string, Parameter|ParameterFactory>
     */
    private function mapParams(Parameters|null $parameters): array
    {
        $map = [];
        foreach ($parameters?->toArray() ?? [] as $param) {
            if ($param instanceof ParameterFactory) {
                $map[$param->component()->getName() . ':' . $param->component()->getLocation()] = $param;
            } else {
                $map[$param->getName() . ':' . $param->getLocation()] = $param;
            }
        }

        return $map;
    }
}
