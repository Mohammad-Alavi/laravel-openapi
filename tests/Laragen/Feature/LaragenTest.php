<?php

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use MohammadAlavi\Laragen\Laragen;
use MohammadAlavi\Laragen\RuleParsers\RequiredWith;
use Tests\Laragen\Support\Doubles\ExtractController;
use Tests\Laragen\Support\Doubles\RequireWith\RequireWithController;

describe(class_basename(Laragen::class), function () {
    it('can get body parameters from a route', function () {
        $route = RouteFacade::get('test', [ExtractController::class, 'simpleRules']);

        $schema = Laragen::getBodyParameters($route);

        expect($schema->toArray())->toBe([
            'type' => 'object',
            'properties' => [
                'foo' => [
                    'type' => 'string',
                    'minLength' => 3,
                ],
                'bar' => [
                    'type' => 'integer',
                ],
            ],
            'required' => ['bar'],
        ]);
    });

    it('returns an empty schema if no rules are found', function () {
        $route = RouteFacade::get('test', [ExtractController::class, 'noRules']);

        $schema = Laragen::getBodyParameters($route);

        expect($schema->toArray())->toBe([
            'type' => 'object',
            'properties' => [],
        ]);
    })->todo();

    $name = [
        'properties' => [
            'name' => [
                'type' => 'string',
                'maxLength' => 20,
                'minLength' => 3,
            ],
        ],
        'required' => ['email'],
    ];

    $email = [
        'properties' => [
            'email' => [
                'type' => 'string',
                'format' => 'email',
            ],
        ],
        'required' => ['name'],
    ];

    $anyOf = [
        $name,
        $email,
    ];

    $password = [
        'password' => [
            'type' => 'string',
            'minLength' => 8,
        ],
    ];

    $passwordConfirmed = [
        'password_confirmed' => [
            'type' => 'string',
            'minLength' => 8,
        ],
    ];

    $address = [
        'address' => [
            'type' => 'string',
            'maxLength' => 255,
        ],
    ];

    $age = [
        'age' => [
            'type' => ['null', 'integer'],
        ],
    ];

    it(
        'can parse required_with rules',
        function (Route $route, array $expectation): void {
            //            dd(Laragen::getBodyParameters($route)->toArray());
            expect(Laragen::getBodyParameters($route)->toArray())->toEqualCanonicalizing($expectation);
        },
    )->with([
        [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'justTwoRequireWithRules']),
            [
                'type' => 'object',
                'anyOf' => [
                    $name,
                    $email,
                ],
            ],
        ],
        [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'justThreeRequireWithRules']),
            [
                'type' => 'object',
                'anyOf' => [
                    $name,
                    $email,
                    [
                        'properties' => $age,
                        'required' => ['name', 'email'],
                    ],
                ],
            ],
        ],
        [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'withAfterRule']),
            [
                'type' => 'object',
                'allOf' => [
                    [
                        'anyOf' => [
                            $name,
                            $email,
                        ],
                    ],
                    [
                        'properties' => $age,
                    ],
                ],
            ],
        ],
        [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'withBeforeAfterRules']),
            [
                'type' => 'object',
                'allOf' => [
                    [
                        'anyOf' => [
                            $name,
                            $email,
                        ],
                    ],
                    [
                        'properties' => [
                            ...$password,
                            ...$passwordConfirmed,
                            ...$address,
                            ...$age,
                        ],
                        'required' => ['address', 'password', 'password_confirmed'],
                    ],
                ],
            ],
        ],
        [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'withoutRequireWithRule']),
            [
                'type' => 'object',
                'properties' => $age,
            ],
        ],
        [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'withMixedOrderRulesRequest']),
            [
                'type' => 'object',
                'allOf' => [
                    [
                        'anyOf' => [
                            $email,
                            $name,
                        ],
                    ],
                    [
                        'properties' => [
                            ...$password,
                            ...$passwordConfirmed,
                            ...$address,
                            ...$age,
                        ],
                        'required' => ['address', 'password', 'password_confirmed'],
                    ],
                ],
            ],
        ],
        [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'anotherWithMixedOrderRulesRequest']),
            [
                'type' => 'object',
                'allOf' => [
                    [
                        'anyOf' => [
                            $name,
                            $email,
                        ],
                    ],
                    [
                        'properties' => [
                            ...$password,
                            ...$passwordConfirmed,
                            ...$age,
                            ...$address,
                        ],
                        'required' => ['address', 'password', 'password_confirmed'],
                    ],
                ],
            ],
        ],
        [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'withEverythingMixedRulesRequest']),
            [
                'type' => 'object',
                'allOf' => [
                    [
                        'anyOf' => [
                            $email,
                            $name,
                        ],
                    ],
                    [
                        'properties' => [
                            ...$password,
                            ...$passwordConfirmed,
                            ...$address,
                            ...$age,
                        ],
                        'required' => ['address', 'password', 'password_confirmed'],
                    ],
                ],
            ],
        ],
    ]);

    it('can generate valid openapi spec', function (): void {
        $spec = Laragen::generate('Autogenerated');

        $spec->toJsonFile('autogenerated', 'tests', JSON_PRETTY_PRINT);

        expect('tests/autogenerated.json')->toBeValidJsonSchema();
        $this->pushCleanupCallback(
            static function () {
                \Safe\unlink('tests/autogenerated.json');
            },
        );
    });
})->covers(Laragen::class, RequiredWith::class);
