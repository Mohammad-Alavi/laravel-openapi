<?php

namespace Tests\oooas\Unit\Support;

use MohammadAlavi\ObjectOrientedOpenAPI\Support\ParameterLocation;

describe(class_basename(ParameterLocation::class), function (): void {
    it('has query case', function (): void {
        expect(ParameterLocation::QUERY->value)->toBe('query');
    });

    it('has header case', function (): void {
        expect(ParameterLocation::HEADER->value)->toBe('header');
    });

    it('has path case', function (): void {
        expect(ParameterLocation::PATH->value)->toBe('path');
    });

    it('has cookie case', function (): void {
        expect(ParameterLocation::COOKIE->value)->toBe('cookie');
    });

    it('has querystring case for whole query string (OAS 3.2)', function (): void {
        expect(ParameterLocation::QUERYSTRING->value)->toBe('querystring');
    });

    it('can be created from valid string values', function (): void {
        expect(ParameterLocation::from('query'))->toBe(ParameterLocation::QUERY)
            ->and(ParameterLocation::from('header'))->toBe(ParameterLocation::HEADER)
            ->and(ParameterLocation::from('path'))->toBe(ParameterLocation::PATH)
            ->and(ParameterLocation::from('cookie'))->toBe(ParameterLocation::COOKIE)
            ->and(ParameterLocation::from('querystring'))->toBe(ParameterLocation::QUERYSTRING);
    });

    it('throws exception for invalid string value', function (): void {
        expect(fn () => ParameterLocation::from('invalid'))->toThrow(\ValueError::class);
    });
})->covers(ParameterLocation::class);
