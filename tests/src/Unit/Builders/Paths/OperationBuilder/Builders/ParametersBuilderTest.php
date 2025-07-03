<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Builders\ParametersBuilder;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use Tests\src\Support\Doubles\Stubs\Attributes\TestParametersFactory;
use Tests\src\Support\Doubles\Stubs\Builders\Paths\Operations\TestController;

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
        expect($parameters->unserializeToArray())->toHaveCount(2)
            ->and($typeHintedParam->unserializeToArray()['schema']['type'])->toBe(Type::integer()->value());
    });

    it('doesnt extract path parameters if there are none', function (): void {
        $builder = new ParametersBuilder();

        $parameters = $builder->pathParameters('/example');

        expect($parameters)->toHaveCount(0);
    });

    it(
        'can extract path parameters',
        function (string $endpoint, int $count, Collection $expectation): void {
            $builder = new ParametersBuilder();

            $parameters = $builder->pathParameters($endpoint);

            expect($parameters)->toEqual($expectation);
        },
    )->with([
        'single parameter' => [
            '/example/{id}',
            1,
            collect([
                [
                    'name' => 'id',
                    'required' => true,
                ],
            ]),
        ],
        'multiple parameters' => [
            '/example/{id}/{name}',
            2,
            collect([
                [
                    'name' => 'id',
                    'required' => true,
                ],
                [
                    'name' => 'name',
                    'required' => true,
                ],
            ]),
        ],
        'optional parameter' => [
            '/example/{id?}',
            1,
            collect([
                [
                    'name' => 'id',
                    'required' => false,
                ],
            ]),
        ],
        'mixed parameters' => [
            '/example/{id}/{name?}',
            2,
            collect([
                [
                    'name' => 'id',
                    'required' => true,
                ],
                [
                    'name' => 'name',
                    'required' => false,
                ],
            ]),
        ],
        'mixed parameters with different order' => [
            '/example/{name?}/{id}',
            2,
            collect([
                [
                    'name' => 'name',
                    'required' => false,
                ],
                [
                    'name' => 'id',
                    'required' => true,
                ],
            ]),
        ],
    ]);
})->covers(ParametersBuilder::class);
