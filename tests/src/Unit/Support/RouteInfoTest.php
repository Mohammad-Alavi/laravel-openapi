<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use Tests\src\Support\Doubles\Stubs\Objects\InvocableController;
use Tests\src\Support\Doubles\Stubs\Objects\MultiActionController;

describe(class_basename(RouteInfo::class), function (): void {
    it('can be created with all parameters', function (): void {
        $routeInformation = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example')
                ->name('example')
                ->domain('laragen.io'),
        );

        expect($routeInformation)->toBeInstanceOf(RouteInfo::class)
            ->domain()->toBe('laragen.io')
            ->method()->toBe('get')
            ->uri()->toBe('/example')
            ->name()->toBe('example')
            ->controller()->toBe('Closure')
            ->controllerAttributes()->toBeInstanceOf(Collection::class)
            ->controllerAttributes()->toHaveCount(0)
            ->action()->toBe('Closure')
            ->actionParameters()->toBeArray()
            ->actionParameters()->toHaveCount(0)
            ->actionAttributes()->toBeInstanceOf(Collection::class)
            ->actionAttributes()->toHaveCount(0);
    });

    it('can handle unsupported http method', function (string $method): void {
        expect(
            function () use ($method): void {
                RouteInfo::create(
                    Route::match(
                        [$method],
                        '/example',
                        static fn (): string => 'example',
                    ),
                );
            },
        )->toThrow(
            InvalidArgumentException::class,
            'Unsupported HTTP method [' . $method . '] for route: example',
        );
    })->with([
        'head' => ['HEAD'],
        'options' => ['OPTIONS'],
    ]);

    $possibleActions = [
        'string action' => [
            'action' => 'Tests\src\Support\Doubles\Stubs\Objects\MultiActionController@example',
            'method' => 'example',
            'controller' => MultiActionController::class,
        ],
        'string action with action' => [
            'action' => [MultiActionController::class, 'example'],
            'method' => 'example',
            'controller' => MultiActionController::class,
        ],
        'string action with invokable action' => [
            'action' => [InvocableController::class, '__invoke'],
            'method' => '__invoke',
            'controller' => InvocableController::class,
        ],
        'invokable controller' => [
            'action' => [InvocableController::class],
            'method' => '__invoke',
            'controller' => InvocableController::class,
        ],
    ];
    it('can be created with all valid combinations', function (array $method, array $actions): void {
        foreach ($actions as $action) {
            $routeInformation = RouteInfo::create(
                Route::match($method, '/example', $action['action']),
            );

            expect($routeInformation)->toBeInstanceOf(RouteInfo::class)
                ->and($routeInformation->action())->toBe($action['method'])
                ->and($routeInformation->controller())->toBe($action['controller']);
        }
    })->with([
        'get' => [
            ['get'],
            'actions' => $possibleActions,
        ],
        'post' => [
            ['post'],
            'actions' => $possibleActions,
        ],
        'put' => [
            ['put'],
            'actions' => $possibleActions,
        ],
        'patch' => [
            ['patch'],
            'actions' => $possibleActions,
        ],
        'delete' => [
            ['delete'],
            'actions' => $possibleActions,
        ],
        'any' => [
            ['any'],
            'actions' => $possibleActions,
        ],
        'mixed valid & invalid' => [
            ['POST', 'HEAD'],
            'actions' => $possibleActions,
        ],
    ]);

    it(
        'can collect and instantiate attributes',
        function (array $action, int $controllerAttrCount, int $methodAttrCount): void {
            $routeInformation = RouteInfo::create(Route::get('/example', $action));

            expect($routeInformation->controllerAttributes())->toHaveCount($controllerAttrCount)
                ->and($routeInformation->actionAttributes())->toHaveCount($methodAttrCount);
        },
    )->with([
        'only controller' => [
            [InvocableController::class],
            1,
            0,
        ],
        'both a' => [
            [MultiActionController::class, 'example'],
            2,
            2,
        ],
        'both b' => [
            [MultiActionController::class, 'anotherExample'],
            2,
            1,
        ],
    ]);
})->covers(RouteInfo::class);
