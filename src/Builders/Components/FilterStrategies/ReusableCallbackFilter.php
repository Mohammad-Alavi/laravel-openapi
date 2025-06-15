<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\FilterStrategy;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;

final readonly class ReusableCallbackFilter implements FilterStrategy
{
    public function apply(Collection $data): Collection
    {
        return $data->filter(
            static fn (string $class): bool => is_a($class, CallbackFactory::class, true),
        );
    }
}
