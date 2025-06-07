<?php

use MohammadAlavi\ObjectOrientedOpenAPI\Trash\SecurityRequirementOld;
use Tests\Doubles\Stubs\SecuritySchemes\ApiKeySecuritySchemeFactory;
use Tests\Doubles\Stubs\SecuritySchemes\BearerSecuritySchemeFactory;
use Tests\Doubles\Stubs\SecuritySchemes\JwtSecuritySchemeFactory;

describe('SecurityRequirement', function (): void {
    it('can set nested security schemes', function (): void {
        $securityRequirementOld = SecurityRequirementOld::create()
            ->nestedSecurityScheme(
                [
                    [
                        (new BearerSecuritySchemeFactory())->build(),
                        (new ApiKeySecuritySchemeFactory())->build(),
                    ],
                    (new JwtSecuritySchemeFactory())->build(),
                ],
            );

        expect($securityRequirementOld->asArray())->toBe([
            [
                'Bearer' => [],
                'ApiKey' => [],
            ],
            [
                'JWT' => [],
            ],
        ]);
    });
})->covers(SecurityRequirementOld::class)->skip();
