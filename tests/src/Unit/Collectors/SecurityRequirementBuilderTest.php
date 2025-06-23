<?php

// use MohammadAlavi\LaravelOpenApi\Builders\SecurityRequirementBuilder;
// use Tests\src\Support\Doubles\Stubs\Objects\ASecuritySchemeFactory;
// use Tests\src\Support\Doubles\Stubs\Objects\BSecuritySchemeFactory;
//
// describe('SecurityRequirementBuilder', function (): void {
//    it('can be created with multi security', function (string|array|null $factories, array $expectation): void {
//        $builder = new SecurityRequirementBuilder();
//
//        $securityRequirement = $builder->build($factories);
//
//        expect($securityRequirement->asArray())->toBe($expectation);
//    })->with([
//        'null' => [
//            null,
//            [
//                ['DefaultSecurityScheme' => []],
//            ],
//        ],
//        'empty string' => [
//            '',
//            [
//                ['DefaultSecurityScheme' => []],
//            ],
//        ],
//        'empty array [] security' => [
//            [],
//            [
//                ['NoSecurityScheme' => []],
//            ],
//        ],
//        'class string security' => [
//            ASecuritySchemeFactory::class,
//            [
//                ['ASecuritySchemeFactory' => []],
//            ],
//        ],
//        'nested single class string security' => [
//            [[ASecuritySchemeFactory::class]],
//            [
//                ['ASecuritySchemeFactory' => []],
//            ],
//        ],
//        'nested one multi class string security' => [
//            [[ASecuritySchemeFactory::class, BSecuritySchemeFactory::class]],
//            [
//                ['ASecuritySchemeFactory' => [], 'BSecuritySchemeFactory' => []],
//            ],
//        ],
//        'nested multiple multi class string security' => [
//            [[ASecuritySchemeFactory::class, BSecuritySchemeFactory::class],
//                [BSecuritySchemeFactory::class, ASecuritySchemeFactory::class], ],
//            [
//                ['ASecuritySchemeFactory' => [], 'BSecuritySchemeFactory' => []],
//                ['BSecuritySchemeFactory' => [], 'ASecuritySchemeFactory' => []],
//            ],
//        ],
//        'nested mixed multi class string security' => [
//            [BSecuritySchemeFactory::class, [BSecuritySchemeFactory::class, ASecuritySchemeFactory::class]],
//            [
//                ['BSecuritySchemeFactory' => []],
//                ['BSecuritySchemeFactory' => [], 'ASecuritySchemeFactory' => []],
//            ],
//        ],
//        'one element array security' => [
//            [ASecuritySchemeFactory::class],
//            [
//                ['ASecuritySchemeFactory' => []],
//            ],
//        ],
//        'multi element array security' => [
//            [ASecuritySchemeFactory::class, BSecuritySchemeFactory::class],
//            [
//                [
//                    'ASecuritySchemeFactory' => [],
//                ],
//                [
//                    'BSecuritySchemeFactory' => [],
//                ],
//            ],
//        ],
//    ]);
//
//    it('throws exception when invalid security configuration', function (string|array|null $factories): void {
//        expect(function () use ($factories): void {
//            $builder = new SecurityRequirementBuilder();
//
//            $builder->build($factories);
//        })->toThrow(RuntimeException::class, 'Invalid security configuration');
//    })->with([
//        'null array' => [
//            [null],
//            [],
//        ],
//        'empty string array' => [
//            [''],
//            [],
//        ],
//        'nested null array' => [
//            [[null]],
//            [],
//        ],
//        'nested empty string array' => [
//            [['']],
//            [],
//        ],
//        'nested empty array' => [
//            [[]],
//            [],
//        ],
//        'nested empty array with empty array' => [
//            [[[]]],
//            [],
//        ],
//        'nested single class string security with empty array' => [
//            [[ASecuritySchemeFactory::class, []]],
//            [
//                ['ASecuritySchemeFactory' => []],
//            ],
//        ],
//    ]);
//
//    it('can be created using security method', function (): void {
//    })->todo();
// })->covers(SecurityRequirementBuilder::class)->skip();
