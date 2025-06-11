<?php

namespace MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ReusableParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Utilities\Generatable;

final class ParameterCollection extends Generatable
{
    private readonly array $parameters;

    private function __construct(
        Parameter|Reference ...$parameter,
    ) {
        $this->parameters = $parameter;
    }

    public static function create(Parameter|ReusableParameterFactory|self ...$parameter): self
    {
        $selfParams = collect($parameter)
            ->filter(static fn ($param): bool => $param instanceof self)
            ->map(static fn ($param): array => $param->all())
            ->flatten();

        $parameters = collect($parameter)
            ->reject(static fn ($param): bool => $param instanceof self)
            ->merge($selfParams)
            ->map(
                static fn (
                    $param,
                ): Reference|self|Parameter => $param instanceof ReusableParameterFactory
                    ? $param::ref()
                    : $param,
            )
            ->toArray();

        return new self(...$parameters);
    }

    public function all(): array
    {
        return $this->parameters;
    }

    protected function toArray(): array
    {
        return $this->parameters;
    }
}
