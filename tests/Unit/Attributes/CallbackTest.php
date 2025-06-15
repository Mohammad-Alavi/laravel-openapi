<?php

use MohammadAlavi\LaravelOpenApi\Attributes\Callback;
use Tests\Doubles\Stubs\Attributes\TestCallbackFactory;
use Tests\Doubles\Stubs\Attributes\TestCallbackFactoryInvalid;

describe('Callable', function (): void {
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
