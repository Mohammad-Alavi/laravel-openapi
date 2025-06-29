<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Components\FilterStrategies;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Contracts\Interface\FilterStrategy;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;

final readonly class CallbackFilter implements FilterStrategy
{
    public function apply(Collection $data): Collection
    {
        return $data->filter(
            static function (string $class): bool {
                return is_a($class, CallbackFactory::class, true)
                    && is_a($class, ShouldBeReferenced::class, true);
            },
        );
    }
}
