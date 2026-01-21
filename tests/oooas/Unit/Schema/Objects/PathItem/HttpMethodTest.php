<?php

namespace Tests\oooas\Unit\Schema\Objects\PathItem;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\PathItem\Support\HttpMethod;

describe(class_basename(HttpMethod::class), function (): void {
    it('has get case', function (): void {
        expect(HttpMethod::GET->value)->toBe('get');
    });

    it('has put case', function (): void {
        expect(HttpMethod::PUT->value)->toBe('put');
    });

    it('has post case', function (): void {
        expect(HttpMethod::POST->value)->toBe('post');
    });

    it('has delete case', function (): void {
        expect(HttpMethod::DELETE->value)->toBe('delete');
    });

    it('has options case', function (): void {
        expect(HttpMethod::OPTIONS->value)->toBe('options');
    });

    it('has head case', function (): void {
        expect(HttpMethod::HEAD->value)->toBe('head');
    });

    it('has patch case', function (): void {
        expect(HttpMethod::PATCH->value)->toBe('patch');
    });

    it('has trace case', function (): void {
        expect(HttpMethod::TRACE->value)->toBe('trace');
    });

    it('has query case for QUERY http method (OAS 3.2)', function (): void {
        expect(HttpMethod::QUERY->value)->toBe('query');
    });

    it('can be created from valid string values', function (): void {
        expect(HttpMethod::from('get'))->toBe(HttpMethod::GET)
            ->and(HttpMethod::from('put'))->toBe(HttpMethod::PUT)
            ->and(HttpMethod::from('post'))->toBe(HttpMethod::POST)
            ->and(HttpMethod::from('delete'))->toBe(HttpMethod::DELETE)
            ->and(HttpMethod::from('options'))->toBe(HttpMethod::OPTIONS)
            ->and(HttpMethod::from('head'))->toBe(HttpMethod::HEAD)
            ->and(HttpMethod::from('patch'))->toBe(HttpMethod::PATCH)
            ->and(HttpMethod::from('trace'))->toBe(HttpMethod::TRACE)
            ->and(HttpMethod::from('query'))->toBe(HttpMethod::QUERY);
    });

    it('throws exception for invalid string value', function (): void {
        expect(fn () => HttpMethod::from('invalid'))->toThrow(\ValueError::class);
    });
})->covers(HttpMethod::class);
