<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use MohammadAlavi\LaravelOpenApi\Attributes\Extension;
use MohammadAlavi\LaravelOpenApi\Attributes\Operation;
use MohammadAlavi\LaravelOpenApi\Attributes\PathItem;
use MohammadAlavi\LaravelOpenApi\Support\RouteInfo;
use Tests\src\Support\Doubles\Stubs\Objects\ControllerWithExtensions;
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

    it('can get operation attribute', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/example', [ControllerWithExtensions::class, 'withExtensions']),
        );

        $operationAttribute = $routeInfo->operationAttribute();

        expect($operationAttribute)->toBeInstanceOf(Operation::class)
            ->and($operationAttribute->summary)->toBe('Test operation');
    });

    it('returns null when no operation attribute exists', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );

        expect($routeInfo->operationAttribute())->toBeNull();
    });

    it('can get path item attribute', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/example', [ControllerWithExtensions::class, 'withExtensions']),
        );

        $pathItemAttribute = $routeInfo->pathItemAttribute();

        expect($pathItemAttribute)->toBeInstanceOf(PathItem::class)
            ->and($pathItemAttribute->summary)->toBe('Test path item');
    });

    it('returns null when no path item attribute exists', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/example', static fn (): string => 'example'),
        );

        expect($routeInfo->pathItemAttribute())->toBeNull();
    });

    it('can get extension attributes', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/example', [ControllerWithExtensions::class, 'withExtensions']),
        );

        $extensions = $routeInfo->extensionAttributes();

        expect($extensions)->toHaveCount(2)
            ->and($extensions->first())->toBeInstanceOf(Extension::class)
            ->and($extensions->first()->key)->toBe('x-custom');
    });

    it('returns empty collection when no extension attributes exist', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/example', [ControllerWithExtensions::class, 'withoutExtensions']),
        );

        expect($routeInfo->extensionAttributes())->toHaveCount(0);
    });

    it('can access collection matcher', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/example', [ControllerWithExtensions::class, 'withExtensions']),
        );

        expect($routeInfo->collection())->toBeInstanceOf(MohammadAlavi\LaravelOpenApi\Support\CollectionMatcher::class);
    });

    it('returns same collection matcher instance on multiple calls', function (): void {
        $routeInfo = RouteInfo::create(
            Route::get('/example', [ControllerWithExtensions::class, 'withExtensions']),
        );

        $matcher1 = $routeInfo->collection();
        $matcher2 = $routeInfo->collection();

        expect($matcher1)->toBe($matcher2);
    });
})->covers(RouteInfo::class);
