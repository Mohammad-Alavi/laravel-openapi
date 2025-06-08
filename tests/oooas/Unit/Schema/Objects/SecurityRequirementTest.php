<?php

namespace Tests\oooas\Unit\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\SecurityScheme;
use MohammadAlavi\ObjectOrientedOpenAPI\Trash\SecurityRequirementOld;

describe('SecurityRequirement', function (): void {
    it('can be created with no parameters', function (): void {
        $securityRequirementOld = SecurityRequirementOld::create();

        expect($securityRequirementOld->asArray())->toBe([]);
    });

    it('can be created with all parameters', function (SecurityScheme|string $securityScheme, $expectation): void {
        $securityRequirementOld = SecurityRequirementOld::create()
            ->securityScheme($securityScheme)
            ->scopes('read:user');

        expect($securityRequirementOld->asArray())->toBe($expectation);
    })->with([
        'security object' => [
            SecurityScheme::create('SecObj'),
            [
                'SecObj' => ['scopes' => ['read:user']],
            ],
        ],
        'string security' => [
            'SecStr',
            [
                'SecStr' => ['scopes' => ['read:user']],
            ],
        ],
    ]);

    it('can be created with no scopes', function (SecurityScheme|string|null $securityScheme, array $expectation): void {
        $securityRequirementOld = SecurityRequirementOld::create()
            ->securityScheme($securityScheme);

        expect($securityRequirementOld->asArray())->toBe($expectation);
    })->with([
        'security scheme object' => [SecurityScheme::create('OAuth2'), [['OAuth2' => []]]],
        'security scheme name' => ['OAuth2', [['OAuth2' => []]]],
        // TODO: Are these two even valid OpenAPI 3.1 objects? I mean, with empty '' security name!
        'null security scheme name' => [null, []],
        'empty security scheme name' => ['', [['' => []]]],
    ]);

    it('can be created with scopes', function (...$scopes): void {
        $securityRequirementOld = SecurityRequirementOld::create()
            ->securityScheme('OAuth2')
            ->scopes(...$scopes);

        expect($securityRequirementOld->asArray())->toBe(['OAuth2' => ['scopes' => $scopes]]);
    })->with([
        'with single scope' => ['read:user'],
        'with multiple scopes' => ['read:user', 'write:user'],
    ]);
})->covers(SecurityRequirementOld::class)->skip();
