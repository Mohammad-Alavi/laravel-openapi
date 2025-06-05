<?php

use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\Parameters as ParameterAttribute;
use MohammadAlavi\LaravelOpenApi\Builders\Paths\Operation\ParametersBuilder;
use MohammadAlavi\LaravelOpenApi\Objects\RouteInfo;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Parameter\Parameter;
use Tests\Doubles\Stubs\Attributes\ParameterFactory;
use Tests\Doubles\Stubs\Collectors\Paths\Operations\TestController;

describe('ParameterBuilder', function (): void {
    it('can be created', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );
        $routeInformation->actionAttributes = collect([
            new ParameterAttribute(ParameterFactory::class),
        ]);
        $builder = new ParametersBuilder();

        $parameterCollection = $builder->build($routeInformation);

        expect($parameterCollection)->not()->toBeNull()
            ->and($parameterCollection->all())->toHaveCount(4);
    });

    it('can automatically create parameters from url params', function (array $params, int $count): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example/{id}', [TestController::class, 'actionWithTypeHintedParams']),
        );
        $routeInformation->actionAttributes = collect($params);
        $builder = new ParametersBuilder();

        $parameterCollection = $builder->build($routeInformation);

        $urlParam = $parameterCollection->all()[0];
        expect($parameterCollection->all())->toHaveCount($count)
            ->and($urlParam)->toBeInstanceOf(Parameter::class);
    })->with([
        'with action params' => [
            'params' => [new ParameterAttribute(ParameterFactory::class)],
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

        $parameterCollection = $builder->build($routeInformation);

        $typeHintedParam = $parameterCollection->all()[0];
        expect($parameterCollection->asArray())->toHaveCount(2)
            ->and($typeHintedParam->schema->jsonSerialize()['type'])->toBe(Type::integer()->value());
    });
})->covers(ParametersBuilder::class);
