<?php

use MohammadAlavi\LaravelOpenApi\Attributes\RequestBody;
use Tests\Doubles\Stubs\Attributes\TestRequestBodyFactory;
use Tests\Doubles\Stubs\Attributes\TestRequestBodyFactoryInvalid;

describe('RequestBody', function (): void {
    it('can set valid factory', function (): void {
        $RequestBody = new RequestBody(factory: TestRequestBodyFactory::class);
        expect($RequestBody->factory)->toBe(TestRequestBodyFactory::class);
    });

    it('can handle invalid factory', function (): void {
        expect(function (): void {
            new RequestBody(factory: TestRequestBodyFactoryInvalid::class);
        })->toThrow(InvalidArgumentException::class);
    });

    it('can handle none existing factory', function (): void {
        expect(function (): void {
            new RequestBody(factory: 'NonExistentFactory');
        })->toThrow(InvalidArgumentException::class);
    });
})->covers(RequestBody::class);
