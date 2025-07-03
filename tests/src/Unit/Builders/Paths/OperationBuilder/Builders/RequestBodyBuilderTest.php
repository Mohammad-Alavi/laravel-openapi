<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\RequestBody as RequestBodyAttribute;
use MohammadAlavi\LaravelOpenApi\Builders\RequestBodyBuilder;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\RequestBodyFactory;
use Tests\src\Support\Doubles\Stubs\Builders\Paths\Operations\TestRequestBodyFactory;

describe(class_basename(RequestBodyBuilder::class), function (): void {
    it('can be created', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([
            new RequestBodyAttribute(TestRequestBodyFactory::class),
        ]);
        $builder = new RequestBodyBuilder();

        $result = $builder->build($routeInformation->requestBodyAttribute());

        expect($result)->toBeInstanceOf(RequestBodyFactory::class);
    });
})->covers(RequestBodyBuilder::class);
