<?php

use MohammadAlavi\Laragen\Support\RouteSpecCollector;
use Tests\Laragen\Support\Doubles\PathController;

describe(class_basename(RouteSpecCollector::class), function (): void {
    it('normalizes php types correctly', function (): void {
        $map = [
            'int' => 'integer',
            'integer' => 'integer',
            'bool' => 'boolean',
            'boolean' => 'boolean',
            'array' => 'array',
            'string' => 'string',
            'mixed' => 'string',
            'OBJECT' => 'string',
        ];
        foreach ($map as $input => $expected) {
            $collector = new RouteSpecCollector();
            $actual = $collector->normalizePhpType($input);

            expect($actual)->toBe($expected);
        }
    });

    it('skips FormRequest in path params', function (): void {
        $route = Route::get('test/{foo}', [PathController::class, 'methodWithFormRequest']);
        $collector = new RouteSpecCollector();

        $pathParams = $collector->pathParams($route);

        expect($pathParams)->toBe(
            [
                'foo' => [
                    'type' => 'string',
                    'required' => true,
                ],
            ],
        );
    });
})->covers(RouteSpecCollector::class);
