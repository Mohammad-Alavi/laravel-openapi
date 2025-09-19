<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Builders\ParametersBuilder;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use Tests\src\Support\Doubles\Stubs\Attributes\TestParametersFactory;
use Tests\src\Support\Doubles\Stubs\Builders\TestController;

describe(class_basename(ParametersBuilder::class), function (): void {
    it('can be created', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([new Operation(parameters: TestParametersFactory::class)]);
        $builder = new ParametersBuilder();

        $parameters = $builder->build($routeInformation);

        expect($parameters)->not()->toBeNull()
            ->and($parameters->toArray())->toHaveCount(4);
    });

    it('can automatically create parameters from url params', function (array $params, int $count): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example/{id}', [TestController::class, 'actionWithTypeHintedParams']),
        );
        $routeInformation->actionAttributes = collect($params);

        $builder = new ParametersBuilder();

        $parameters = $builder->build($routeInformation);

        $urlParam = $parameters->toArray()[0];
        expect($parameters->toArray())->toHaveCount($count)
            ->and($urlParam)->toBeInstanceOf(Parameter::class);
    })->with([
        'with action params' => [
            'params' => [new Operation(parameters: TestParametersFactory::class)],
            'count' => 5,
        ],
        'without action params' => [
            'params' => [],
            'count' => 1,
        ],
    ]);

    it('can guess parameter name if it is type hinted in controller method', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example/{id}/{unHinted}/{unknown}', [TestController::class, 'actionWithTypeHintedParams']),
        );
        $builder = new ParametersBuilder();

        $parameters = $builder->build($routeInformation);

        $typeHintedParam = $parameters->toArray()[0];
        expect($parameters->compile())->toHaveCount(2)
            ->and($typeHintedParam->compile()['schema']['type'])->toBe(Type::integer()->value());
    });

    it('doesnt extract path parameters if there are none', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $builder = new ParametersBuilder();

        $parameters = $builder->build($routeInformation);

        expect($parameters)->compile()->toHaveCount(0);
    });

    it(
        'can extract path parameters',
        function (string $endpoint, array $expectation): void {
            $routeInformation = RouteInfo::create(
                Route::get($endpoint, static fn (): string => 'example'),
            );
            $builder = new ParametersBuilder();

            $parameters = $builder->build($routeInformation);

            expect($parameters)->compile()->toEqual($expectation);
        },
    )->with([
        'single parameter' => [
            '/example/{id}',
            [
                [
                    'name' => 'id',
                    'required' => true,
                    'in' => 'path',
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
        'multiple parameters' => [
            '/example/{id}/{name}',
            [
                [
                    'name' => 'id',
                    'required' => true,
                    'in' => 'path',
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
                [
                    'name' => 'name',
                    'required' => true,
                    'in' => 'path',
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
        'optional parameter' => [
            '/example/{id?}',
            [
                [
                    'name' => 'id',
                    'in' => 'path',
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
        'mixed parameters' => [
            '/example/{id}/{name?}',
            [
                [
                    'name' => 'id',
                    'required' => true,
                    'in' => 'path',
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
                [
                    'name' => 'name',
                    'in' => 'path',
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
        'mixed parameters with different order' => [
            '/example/{name?}/{id}',
            [
                [
                    'name' => 'name',
                    'in' => 'path',
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
                [
                    'name' => 'id',
                    'required' => true,
                    'in' => 'path',
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ]);
})->covers(ParametersBuilder::class);
