<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\Auth\AuthScheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\Schemes\Http;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityScheme\SecurityScheme;

describe(class_basename(AuthScheme::class), function (): void {
    it('can create a bearer scheme', function (): void {
        $scheme = AuthScheme::bearer('sanctum');

        expect($scheme->guardName())->toBe('sanctum')
            ->and($scheme->toSecurityScheme()->toArray())->toBe(
                SecurityScheme::http(Http::bearer())->toArray(),
            );
    });

    it('can create a basic scheme', function (): void {
        $scheme = AuthScheme::basic();

        expect($scheme->guardName())->toBeNull()
            ->and($scheme->toSecurityScheme()->toArray())->toBe(
                SecurityScheme::http(Http::basic())->toArray(),
            );
    });

    it('generates a security scheme name from guard for bearer', function (): void {
        expect(AuthScheme::bearer('sanctum')->schemeName())->toBe('sanctum')
            ->and(AuthScheme::bearer('api')->schemeName())->toBe('api');
    });

    it('generates a security scheme name for basic', function (): void {
        expect(AuthScheme::basic()->schemeName())->toBe('basic');
    });

    it('two bearer schemes with the same guard are equal', function (): void {
        $a = AuthScheme::bearer('sanctum');
        $b = AuthScheme::bearer('sanctum');

        expect($a->equals($b))->toBeTrue();
    });

    it('two bearer schemes with different guards are not equal', function (): void {
        $a = AuthScheme::bearer('sanctum');
        $b = AuthScheme::bearer('api');

        expect($a->equals($b))->toBeFalse();
    });

    it('bearer and basic schemes are not equal', function (): void {
        $a = AuthScheme::bearer('sanctum');
        $b = AuthScheme::basic();

        expect($a->equals($b))->toBeFalse();
    });
})->covers(AuthScheme::class);
