<?php

use MohammadAlavi\Laragen\Laragen;
use Tests\Laragen\Support\Doubles\ExtractController;

describe(class_basename(Laragen::class), function () {
    it('can get body parameters from a route', function () {
        $route = Route::get('test', [ExtractController::class, 'simpleRules']);

        $schema = Laragen::getBodyParameters($route);

        expect($schema->toArray())->toBe([
            'type' => 'object',
            'properties' => [
                'foo' => [
                    'type' => 'string',
                    'minLength' => 3,
                ],
                'bar' => [
                    'type' => 'integer',
                ],
            ],
            'required' => ['bar'],
        ]);
    });
})->covers(Laragen::class);
