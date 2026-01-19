<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Builders\ParametersBuilder;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use Tests\src\Support\Doubles\Stubs\Attributes\TestParametersFactory;
use Tests\src\Support\Doubles\Stubs\Builders\TestController;

describe(class_basename(ParametersBuilder::class), function (): void {
    it('can build operation parameters from factory', function (): void {
        $builder = new ParametersBuilder();

        $parameters = $builder->buildForOperation(TestParametersFactory::class);

        expect($parameters)->not()->toBeNull()
            ->and($parameters->toArray())->toHaveCount(4);
    });

    it('can build path item parameters combining uri and factory params', function (string|null $factoryClass, int $count): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example/{id}', [TestController::class, 'actionWithTypeHintedParams']),
        );

        $builder = new ParametersBuilder();

        $parameters = $builder->buildForPathItem($routeInformation, $factoryClass);

        $urlParam = $parameters->toArray()[0];
        expect($parameters->toArray())->toHaveCount($count)
            ->and($urlParam)->toBeInstanceOf(Parameter::class);
    })->with([
        'with factory params' => [
            'factoryClass' => TestParametersFactory::class,
            'count' => 5,
        ],
        'without factory params' => [
            'factoryClass' => null,
            'count' => 1,
        ],
    ]);

    it('can guess parameter name if it is type hinted in controller method', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example/{id}/{unHinted}/{unknown}', [TestController::class, 'actionWithTypeHintedParams']),
        );
        $builder = new ParametersBuilder();

        $parameters = $builder->buildForPathItem($routeInformation, null);

        $typeHintedParam = $parameters->toArray()[0];
        expect($parameters->compile())->toHaveCount(2)
            ->and($typeHintedParam->compile()['schema']['type'])->toBe(Type::integer()->value());
    });

    it('doesnt extract path parameters if there are none', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $builder = new ParametersBuilder();

        $parameters = $builder->buildForPathItem($routeInformation, null);

        expect($parameters)->compile()->toHaveCount(0);
    });

    it(
        'can extract path parameters',
        function (string $endpoint, array $expectation): void {
            $routeInformation = RouteInfo::create(
                Route::get($endpoint, static fn (): string => 'example'),
            );
            $builder = new ParametersBuilder();

            $parameters = $builder->buildForPathItem($routeInformation, null);

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
