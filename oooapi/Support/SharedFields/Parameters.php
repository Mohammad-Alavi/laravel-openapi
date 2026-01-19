<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Generatable;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;

final class Parameters extends Generatable
{
    /**
     * @param (Parameter|ParameterFactory)[] $parameters
     */
    private function __construct(
        private readonly array $parameters,
    ) {
    }

    public static function create(Parameter|ParameterFactory|self ...$parameter): self
    {
        $selfInstances = array_filter(
            $parameter,
            static fn (Parameter|ParameterFactory|self $param): bool => $param instanceof self,
        );
        /** @var (Parameter|ParameterFactory)[] $selfParams */
        $selfParams = array_reduce(
            $selfInstances,
            static function (array $carry, self $param): array {
                return array_merge($carry, $param->toArray());
            },
            [],
        );
        $parameters = array_filter(
            $parameter,
            static fn (Parameter|ParameterFactory|self $param): bool => !($param instanceof self),
        );

        return new self(self::removeDuplicate(array_merge($parameters, $selfParams)));
    }

    /**
     * @return (Parameter|ParameterFactory)[]
     */
    public function toArray(): array
    {
        return $this->parameters;
    }

    /**
     * A unique parameter is defined by a combination of a name and location.
     * When duplicates exist, the last occurrence is kept (later items override earlier ones).
     *
     * @param (Parameter|ParameterFactory)[] $parameters
     *
     * @return (Parameter|ParameterFactory)[]
     */
    private static function removeDuplicate(array $parameters): array
    {
        $uniqueParameters = [];
        foreach ($parameters as $parameter) {
            if ($parameter instanceof Parameter) {
                $key = $parameter->getName() . ':' . $parameter->getLocation();
            } elseif ($parameter instanceof ParameterFactory) {
                $key = $parameter->component()->getName() . ':' . $parameter->component()->getLocation();
            } else {
                continue;
            }

            $uniqueParameters[$key] = $parameter;
        }

        return array_values($uniqueParameters);
    }
}
