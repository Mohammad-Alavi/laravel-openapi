<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\FilterStrategies;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\FilterStrategy;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\ParameterFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;

final readonly class ParameterFilter implements FilterStrategy
{
    public function apply(Collection $data): Collection
    {
        return $data->filter(
            static function (string $class): bool {
                return is_a($class, ParameterFactory::class, true)
                    && is_a($class, ShouldBeReferenced::class, true);
            },
        );
    }
}
