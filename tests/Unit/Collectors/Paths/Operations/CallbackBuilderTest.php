<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\Callback as CallbackAttribute;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\Operation\CallbackBuilder;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Callback;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use Tests\Doubles\Stubs\Attributes\CallbackFactory;
use Tests\Doubles\Stubs\Collectors\Paths\Operations\ReusableComponentCallbackFactory;

describe(class_basename(CallbackBuilder::class), function (): void {
    it('can be created', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([
            new CallbackAttribute(CallbackFactory::class),
        ]);
        $builder = new CallbackBuilder();

        $result = $builder->build($routeInformation);

        expect($result)->toHaveCount(1)
            ->and($result[0])->toBeInstanceOf(Callback::class);
    });

    it('can handle reusable components', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([
            new CallbackAttribute(CallbackFactory::class),
            new CallbackAttribute(ReusableComponentCallbackFactory::class),
        ]);
        $builder = new CallbackBuilder();

        $result = $builder->build($routeInformation);

        $pathItem = $result[0];
        /** @var Reference $reusablePathItem */
        $reusablePathItem = $result[1];
        expect($result)->toHaveCount(2)
            ->and($pathItem)->toBeInstanceOf(Callback::class)
            ->and($reusablePathItem)->toBeInstanceOf(Reference::class)
            ->and($reusablePathItem->asArray())
            ->toBe(
                [
                    '$ref' => '#/components/callbacks/ReusableComponentCallbackFactory',
                ],
            );
    });
})->covers(CallbackBuilder::class);
