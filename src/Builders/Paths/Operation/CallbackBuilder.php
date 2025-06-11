<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\Operation;

use MohammadAlavi\LaravelOpenApi\Attributes\Callback as CallbackAttribute;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\Factories\Components\CallbackFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ReusableRefObj;

class CallbackBuilder
{
    public function build(RouteInfo $routeInfo): array
    {
        return $routeInfo->callbackAttributes()
            ->map(static function (CallbackAttribute $callbackAttribute) {
                /** @var CallbackFactory $factory */
                $factory = app($callbackAttribute->factory);

                if ($factory instanceof ReusableRefObj) {
                    return $factory::ref();
                }

                return $factory->build();
            })
            ->values()
            ->toArray();
    }
}
