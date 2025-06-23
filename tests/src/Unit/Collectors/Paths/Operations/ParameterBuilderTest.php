<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\Parameters as ParameterAttribute;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\OperationBuilder\Builders\ParametersBuilder;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use Tests\src\Support\Doubles\Stubs\Attributes\TestParameterFactory;
use Tests\src\Support\Doubles\Stubs\Collectors\Paths\Operations\TestController;

describe('ParameterBuilder', function (): void {
    it('can be created', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([
            new ParameterAttribute(TestParameterFactory::class),
        ]);
        $builder = new ParametersBuilder();

        $parameters = $builder->build($routeInformation);

        expect($parameters)->not()->toBeNull()
            ->and($parameters->all())->toHaveCount(4);
    });

    it('can automatically create parameters from url params', function (array $params, int $count): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example/{id}', [TestController::class, 'actionWithTypeHintedParams']),
        );
        $routeInformation->actionAttributes = collect($params);

        $builder = new ParametersBuilder();

        $parameters = $builder->build($routeInformation);

        $urlParam = $parameters->all()[0];
        expect($parameters->all())->toHaveCount($count)
            ->and($urlParam)->toBeInstanceOf(Parameter::class);
    })->with([
        'with action params' => [
            'params' => [new ParameterAttribute(TestParameterFactory::class)],
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

        $typeHintedParam = $parameters->all()[0];
        expect($parameters->asArray())->toHaveCount(2)
            ->and($typeHintedParam->asArray()['schema']['type'])->toBe(Type::integer()->value());
    });
})->covers(ParametersBuilder::class);
