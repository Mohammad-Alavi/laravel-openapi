<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders;

use MohammadAlavi\LaravelOpenApi\Attributes\Callback as CallbackAttribute;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Reusable\ReusableComponent;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components\CallbackFactory;

class CallbackBuilder
{
    public function build(RouteInfo $routeInfo): array
    {
        return $routeInfo->callbackAttributes()
            ->map(static function (CallbackAttribute $callbackAttribute) {
                /** @var CallbackFactory $factory */
                $factory = app($callbackAttribute->factory);

                if ($factory instanceof ReusableComponent) {
                    return $factory::reference();
                }

                return $factory->build();
            })
            ->values()
            ->toArray();
    }
}
