<?php

namespace MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders;

use Illuminate\Support\Collection;
use MohammadAlavi\LaravelOpenApi\Attributes\Callback as CallbackAttribute;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;

class CallbackBuilder
{
    public function build(Collection $callbacks): array
    {
        return $callbacks->map(
            static function (CallbackAttribute $callbackAttribute) {
                /** @var CallbackFactory $factory */
                $factory = $callbackAttribute->factory;

                return $factory::create();
            },
        )->values()
            ->toArray();
    }
}
