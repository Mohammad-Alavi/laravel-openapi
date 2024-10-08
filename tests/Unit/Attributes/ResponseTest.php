<?php

use MohammadAlavi\LaravelOpenApi\Attributes\Response;
use Tests\Doubles\Stubs\Attributes\ResponseFactoryInvalid;
use Tests\Doubles\Stubs\Attributes\ResponseFactory;

describe('Response', function (): void {
    it('can set valid factory', function (): void {
        $Response = new Response(factory: ResponseFactory::class);
        expect($Response->factory)->toBe(ResponseFactory::class);
    });

    it('can handle invalid factory', function (): void {
        expect(function (): void {
            new Response(factory: ResponseFactoryInvalid::class);
        })->toThrow(InvalidArgumentException::class);
    });

    it('can handle none existing factory', function (): void {
        expect(function (): void {
            new Response(factory: 'NonExistentFactory');
        })->toThrow(InvalidArgumentException::class);
    });

    it('can handle null status code', function (): void {
        $Response = new Response(factory: ResponseFactory::class);
        expect($Response->statusCode)->toBeNull();
    });

    it('can handle null description', function (): void {
        $Response = new Response(factory: ResponseFactory::class);
        expect($Response->description)->toBeNull();
    });
})->covers(Response::class);
