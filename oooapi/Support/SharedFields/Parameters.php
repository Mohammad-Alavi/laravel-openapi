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

        return new self(array_merge($parameters, $selfParams));
    }

    /**
     * @return (Parameter|ParameterFactory)[]
     */
    public function toArray(): array
    {
        return $this->parameters;
    }
}
