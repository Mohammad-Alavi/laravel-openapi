<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Support\SharedFields;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Generatable;

final class Parameters extends Generatable
{
    /**
     * @param (Parameter|Reference)[] $parameters
     */
    private function __construct(
        private readonly array $parameters,
    ) {
    }

    public static function create(Parameter|ParameterFactory|self ...$parameter): self
    {
        $selfParams = collect($parameter)
            ->filter(static fn ($param): bool => $param instanceof self)
            ->map(static fn (self $param): array => $param->all())
            ->flatten();

        $parameters = collect($parameter)
            ->reject(static fn ($param): bool => $param instanceof self)
            ->merge($selfParams)
            ->map(
                static fn (
                    $param,
                ): Reference|self|Parameter => $param instanceof ParameterFactory
                    ? $param::reference()
                    : $param,
            )->toArray();

        return new self($parameters);
    }

    /**
     * @return (Parameter|Reference)[]
     */
    public function all(): array
    {
        return $this->parameters;
    }

    protected function toArray(): array
    {
        return $this->parameters;
    }
}
