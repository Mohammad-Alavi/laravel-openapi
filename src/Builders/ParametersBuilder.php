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
    /**
     * Build path-level parameters: URI path params + PathItem attribute params.
     * These apply to all operations on this path per OAS spec.
     */
    public function buildForPathItem(RouteInfo $routeInfo, string|null $factoryClass): Parameters
    {
        $uriParams = $this->buildUriPathParams($routeInfo);
        $factoryParams = $factoryClass ? $this->buildFromFactory($factoryClass) : null;

        return Parameters::create(
            ...($uriParams?->toArray() ?? []),
            ...($factoryParams?->toArray() ?? []),
        );
    }

    /**
     * Build operation-level parameters from factory only.
     * Per OAS spec, these override path-level params with same name+location.
     */
    public function buildForOperation(string|null $factoryClass): Parameters|null
    {
        if (is_null($factoryClass)) {
            return null;
        }

        return $this->buildFromFactory($factoryClass);
    }

    /**
     * @deprecated use buildForPathItem() or buildForOperation() instead
     */
    public function build(RouteInfo $routeInfo): Parameters
    {
        $pathParams = $this->buildUriPathParams($routeInfo);

        $attrParams = $routeInfo->operationAttribute()?->parameters;
        $operationParams = $attrParams ? $this->buildFromFactory($attrParams) : null;

        return Parameters::create(
            ...($pathParams?->toArray() ?? []),
            ...($operationParams?->toArray() ?? []),
        );
    }

    private function buildUriPathParams(RouteInfo $routeInfo): Parameters|null
    {
        /** @var Collection $params */
        $params = $this->extractPathParameters($routeInfo->uri())
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

    private function extractPathParameters(string $uri): Collection
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

    /** @param class-string<ParametersFactory> $factoryClass */
    private function buildFromFactory(string $factoryClass): Parameters
    {
        Assert::isAOf($factoryClass, ParametersFactory::class);

        return (new $factoryClass())->build();
    }
}
