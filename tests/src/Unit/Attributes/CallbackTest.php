<?php

use MohammadAlavi\LaravelOpenApi\Attributes\Callback;
use Tests\src\Support\Doubles\Stubs\Attributes\TestCallbackFactory;
use Tests\src\Support\Doubles\Stubs\Attributes\TestCallbackFactoryInvalid;

describe(class_basename(Callback::class), function (): void {
    it('can set valid factory', function (): void {
        $callback = new Callback(TestCallbackFactory::class);
        expect($callback)->toBeInstanceOf(Callback::class);
    });

    it('can handle invalid factory', function (): void {
        expect(function (): void {
            new Callback(TestCallbackFactoryInvalid::class);
        })->toThrow(InvalidArgumentException::class);
    });

    it('can handle non existent factory', function (): void {
        expect(function (): void {
            new Callback('NonExistentFactory');
        })->toThrow(InvalidArgumentException::class);
    });
})->covers(Callback::class);
