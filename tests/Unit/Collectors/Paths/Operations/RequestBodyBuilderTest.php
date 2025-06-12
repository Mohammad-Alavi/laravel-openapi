<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\RequestBody as RequestBodyAttribute;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders\RequestBodyBuilder;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Reference\Reference;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\RequestBody;
use Tests\Doubles\Stubs\Attributes\RequestBodyFactory;
use Tests\Doubles\Stubs\Collectors\Paths\Operations\TestReusableRequestBodyFactory;

describe('RequestBodyBuilder', function (): void {
    it('can be created', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([
            new RequestBodyAttribute(RequestBodyFactory::class),
        ]);
        $builder = new RequestBodyBuilder();

        $result = $builder->build($routeInformation->requestBodyAttribute());

        expect($result)->toBeInstanceOf(RequestBody::class);
    });

    it('can handle reusable components', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([
            new RequestBodyAttribute(TestReusableRequestBodyFactory::class),
        ]);
        $builder = new RequestBodyBuilder();

        $result = $builder->build($routeInformation->requestBodyAttribute());

        expect($result)->toBeInstanceOf(Reference::class)
            ->and($result->asArray())->toBe(
                [
                    '$ref' => '#/components/requestBodies/TestReusableRequestBodyFactory',
                ],
            );
    });
})->covers(RequestBodyBuilder::class);
