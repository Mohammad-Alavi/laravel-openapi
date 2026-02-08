<?php

declare(strict_types=1);

use MohammadAlavi\Laragen\Auth\AuthScheme;
use MohammadAlavi\Laragen\Auth\BasicSecuritySchemeFactory;
use MohammadAlavi\Laragen\Auth\BearerSecuritySchemeFactory;
use MohammadAlavi\Laragen\Auth\SecuritySchemeRegistry;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\Security;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\RequiredSecurity;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Security\SecurityRequirement\SecurityRequirement;

describe(class_basename(SecuritySchemeRegistry::class), function (): void {
    it('builds security for a bearer auth scheme', function (): void {
        $registry = new SecuritySchemeRegistry();

        $security = $registry->securityFor(AuthScheme::bearer('sanctum'));

        expect($security->toArray())->toBe(
            Security::create(
                SecurityRequirement::create(
                    RequiredSecurity::create(BearerSecuritySchemeFactory::create()),
                ),
            )->toArray(),
        );
    });

    it('builds security for a basic auth scheme', function (): void {
        $registry = new SecuritySchemeRegistry();

        $security = $registry->securityFor(AuthScheme::basic());

        expect($security->toArray())->toBe(
            Security::create(
                SecurityRequirement::create(
                    RequiredSecurity::create(BasicSecuritySchemeFactory::create()),
                ),
            )->toArray(),
        );
    });

    it('returns the correct factory class for bearer', function (): void {
        $registry = new SecuritySchemeRegistry();

        $factory = $registry->factoryFor(AuthScheme::bearer('sanctum'));

        expect($factory)->toBeInstanceOf(BearerSecuritySchemeFactory::class);
    });

    it('returns the correct factory class for basic', function (): void {
        $registry = new SecuritySchemeRegistry();

        $factory = $registry->factoryFor(AuthScheme::basic());

        expect($factory)->toBeInstanceOf(BasicSecuritySchemeFactory::class);
    });
})->covers(SecuritySchemeRegistry::class);
