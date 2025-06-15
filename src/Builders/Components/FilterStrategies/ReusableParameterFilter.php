<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\FilterStrategy;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;

final readonly class ReusableParameterFilter implements FilterStrategy
{
    public function apply(Collection $data): Collection
    {
        return $data->filter(
            static fn (string $class): bool => is_a($class, ParameterFactory::class, true),
        );
    }
}
