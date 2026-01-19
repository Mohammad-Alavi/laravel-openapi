<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\ComponentsBuilder\FilterStrategies;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\FilterStrategy;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;

final readonly class ComponentFilter implements FilterStrategy
{
    /**
     * @param class-string $factoryClass
     */
    public function __construct(
        private string $factoryClass,
    ) {
    }

    public function apply(Collection $data): Collection
    {
        return $data->filter(
            fn (string $class): bool => is_a($class, $this->factoryClass, true)
                && is_a($class, ShouldBeReferenced::class, true),
        );
    }
}
