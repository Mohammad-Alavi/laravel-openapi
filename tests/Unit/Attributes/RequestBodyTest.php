<?php

use MohammadAlavi\LaravelOpenApi\Attributes\RequestBody;
use Tests\Doubles\Stubs\Attributes\RequestBodyFactory;
use Tests\Doubles\Stubs\Attributes\RequestBodyFactoryInvalid;

describe('RequestBody', function (): void {
    it('can set valid factory', function (): void {
        $RequestBody = new RequestBody(factory: RequestBodyFactory::class);
        expect($RequestBody->factory)->toBe(RequestBodyFactory::class);
    });

    it('can handle invalid factory', function (): void {
        expect(function (): void {
            new RequestBody(factory: RequestBodyFactoryInvalid::class);
        })->toThrow(InvalidArgumentException::class);
    });

    it('can handle none existing factory', function (): void {
        expect(function (): void {
            new RequestBody(factory: 'NonExistentFactory');
        })->toThrow(InvalidArgumentException::class);
    });
})->covers(RequestBody::class);
