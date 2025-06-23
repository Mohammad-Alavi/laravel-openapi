<?php

// use MohammadAlavi\LaravelOpenApi\Builders\SecurityRequirementBuilder;
// use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Operation\Operation;
// use Tests\src\Support\Doubles\Stubs\Objects\ASecuritySchemeFactory;
// use Tests\src\Support\Doubles\Stubs\Objects\BSecuritySchemeFactory;
//
// describe('Operation', function (): void {
//    it('can set security', function (array $securityReqs, array $expectation): void {
//        $operation = Operation::create();
//
//        $result = $operation->security(...$securityReqs);
//
//        expect($result->asArray())->toBe($expectation);
//    })->with([
//        'empty array [] security' => [
//            [],
//            [],
//        ],
// //        'no security' => [
// //            [(new SecurityRequirementBuilder())->build(NoSecurityScheme::class)],
// //            [
// //                'security' => [],
// //            ],
// //        ],
// //        'default security' => [
// //            [(new SecurityRequirementBuilder())->build(DefaultSecurityScheme::class)],
// //            [],
// //        ],
//        'one element array security' => [
//            [(new SecurityRequirementBuilder())->build(ASecuritySchemeFactory::class)],
//            [
//                'security' => [
//                    [
//                        'ASecuritySchemeFactory' => [],
//                    ],
//                ],
//            ],
//        ],
//        'nested security' => [
//            [
//                (new SecurityRequirementBuilder())->build([
//                    ASecuritySchemeFactory::class,
//                    BSecuritySchemeFactory::class,
//                ]),
//            ],
//            [
//                'security' => [
//                    [
//                        'ASecuritySchemeFactory' => [],
//                    ],
//                    [
//                        'BSecuritySchemeFactory' => [],
//                    ],
//                ],
//            ],
//        ],
//        'multiple nested security' => [
//            [
//                (new SecurityRequirementBuilder())->build([
//                    BSecuritySchemeFactory::class,
//                ]),
//                (new SecurityRequirementBuilder())->build([
//                    ASecuritySchemeFactory::class,
//                    BSecuritySchemeFactory::class,
//                ]),
//            ],
//            [
//                'security' => [
//                    [
//                        'BSecuritySchemeFactory' => [],
//                    ],
//                ],
//            ],
//        ],
//    ]);
// })->covers(Operation::class)->skip();
