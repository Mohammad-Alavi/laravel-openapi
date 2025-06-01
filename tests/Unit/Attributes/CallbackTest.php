<?php

use MohammadAlavi\LaravelOpenApi\Attributes\Callback;
use Tests\Doubles\Stubs\Attributes\CallbackFactory;
use Tests\Doubles\Stubs\Attributes\CallbackFactoryInvalid;

describe('Callable', function (): void {
    it('can set valid factory', function (): void {
        $callback = new Callback(CallbackFactory::class);
        expect($callback)->toBeInstanceOf(Callback::class);
    });

    it('can handle invalid factory', function (): void {
        expect(function (): void {
            new Callback(CallbackFactoryInvalid::class);
        })->toThrow(InvalidArgumentException::class);
    });

    it('can handle non existent factory', function (): void {
        expect(function (): void {
            new Callback('NonExistentFactory');
        })->toThrow(InvalidArgumentException::class);
    });
})->covers(Callback::class);
