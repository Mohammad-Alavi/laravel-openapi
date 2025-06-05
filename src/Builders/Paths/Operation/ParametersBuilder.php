<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\Operation;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Parameters;
use MohammadAlavi\LaravelOpenApi\Collections\ParameterCollection;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\Factories\Collections\ParameterCollectionFactory;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\v31\Schema;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\In\In;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Fields\Name;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;

class ParametersBuilder
{
    public function build(RouteInfo $routeInfo): ParameterCollection
    {
        $parameterCollection = $this->buildPath($routeInfo);
        $attributedParameters = $this->buildAttribute($routeInfo);

        return ParameterCollection::create(
            $parameterCollection,
            $attributedParameters,
        );
    }

    protected function buildPath(RouteInfo $routeInfo): ParameterCollection
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

                return Parameter::create(Name::create($parameter['name']), In::path())
                    ->required()
                    ->schema($schema);
            });
        $parameters = $parameters->filter(static fn (Parameter|null $parameter): bool => !is_null($parameter));

        return ParameterCollection::create(...$parameters->toArray());
    }

    private function guessFromReflectionType(\ReflectionType $reflectionType): JSONSchema
    {
        return match ($reflectionType->getName()) {
            'int' => Schema::integer(),
            'bool' => Schema::boolean(),
            default => Schema::string(),
        };
    }

    protected function buildAttribute(RouteInfo $routeInfo): ParameterCollection
    {
        $parameters = $routeInfo->parametersAttribute();

        if ($parameters instanceof Parameters) {
            /** @var ParameterCollectionFactory $parametersFactory */
            $parametersFactory = app($parameters->factory);

            return $parametersFactory->build();
        }

        return ParameterCollection::create();
    }
}
