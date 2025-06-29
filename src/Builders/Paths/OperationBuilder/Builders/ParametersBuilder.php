<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Parameters as ParametersAttribute;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\ParametersFactory;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\SerializationRule\SchemaSerializedPath;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields\Parameters;

class ParametersBuilder
{
    public function build(RouteInfo $routeInfo): Parameters
    {
        $parameters = $this->buildPath($routeInfo);
        $attributedParameters = $this->buildAttribute($routeInfo);

        return Parameters::create(
            $parameters,
            $attributedParameters,
        );
    }

    protected function buildPath(RouteInfo $routeInfo): Parameters
    {
        /** @var Collection $parameters */
        $parameters = $routeInfo->parameters()
            ->map(function (array $parameter) use ($routeInfo): Parameter|null {
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

                return Parameter::path(
                    $parameter['name'],
                    SchemaSerializedPath::create($schema),
                )->required();
            });
        $parameters = $parameters->filter(
            static function (Parameter|ParameterFactory|null $parameter): bool {
                return !is_null($parameter);
            },
        );

        return Parameters::create(...$parameters->toArray());
    }

    private function guessFromReflectionType(\ReflectionType $reflectionType): JSONSchema
    {
        return match ($reflectionType->getName()) {
            'int' => Schema::integer(),
            'bool' => Schema::boolean(),
            default => Schema::string(),
        };
    }

    protected function buildAttribute(RouteInfo $routeInfo): Parameters
    {
        $parameters = $routeInfo->parametersAttribute();

        if ($parameters instanceof ParametersAttribute) {
            /** @var ParametersFactory $parametersFactory */
            $parametersFactory = app($parameters->factory);

            return $parametersFactory->build();
        }

        return Parameters::create();
    }
}
