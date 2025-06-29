<?php

use MohammadAlavi\LaravelOpenApi\Attributes\Callback as CallbackAttribute;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders\CallbackBuilder;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\CallbackFactory;
use Tests\src\Support\Doubles\Stubs\Attributes\TestCallbackFactory;
use Tests\src\Support\Doubles\Stubs\Builders\Paths\Operations\AnotherTestCallbackFactory;

describe(class_basename(CallbackBuilder::class), function (): void {
    it('can be created', function (): void {
        $actionAttributes = collect([
            new CallbackAttribute(TestCallbackFactory::class),
            new CallbackAttribute(AnotherTestCallbackFactory::class),
        ]);
        $builder = new CallbackBuilder();

        $result = $builder->build($actionAttributes);

        expect($result)
            ->toHaveCount(2)
            ->toContainOnlyInstancesOf(CallbackFactory::class);
    });
})->covers(CallbackBuilder::class);
