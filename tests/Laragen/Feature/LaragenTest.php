<?php

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use MohammadAlavi\Laragen\Laragen;
use MohammadAlavi\Laragen\RuleParsers\RequiredWithoutParser;
use Tests\Laragen\Support\Doubles\ExtractController;
use Tests\Laragen\Support\Doubles\RequireWith\RequireWithController;

describe(class_basename(Laragen::class), function () {
    beforeEach(function () {
        config(['laragen.autogen.example' => false]);
    });

    it('can get body parameters from a route', function () {
        $route = RouteFacade::get('test', [ExtractController::class, 'simpleRules']);

        $schema = Laragen::extractRequestBodySchema($route);

        expect($schema->compile())->toBe([
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

        $schema = Laragen::extractRequestBodySchema($route);

        expect($schema->compile())->toBe([
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
            config(['laragen.autogen.example' => false]);

            expect(Laragen::extractRequestBodySchema($route)->compile())->toEqualCanonicalizing($expectation);
        },
    )->with([
        'justTwoRequireWithRules' => [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'justTwoRequireWithRules']),
            [
                'type' => 'object',
                'anyOf' => [
                    $name,
                    $email,
                ],
            ],
        ],
        'justThreeRequireWithRules' => [
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
        'withAfterRule' => [
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
        'withBeforeAfterRules' => [
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
        'withoutRequireWithRule' => [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'withoutRequireWithRule']),
            [
                'type' => 'object',
                'properties' => $age,
            ],
        ],
        'withMixedOrderRulesRequest' => [
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
        'anotherWithMixedOrderRulesRequest' => [
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
        'withEverythingMixedRulesRequest' => [
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

        $spec->toJsonFile('autogenerated', 'temp/tests', JSON_PRETTY_PRINT);

        expect('temp/tests/autogenerated.json')->toBeValidJsonSchema();
        $this->pushCleanupCallback(
            static function () {
                \Safe\unlink('temp/tests/autogenerated.json');
            },
        );
    });
})->covers(Laragen::class, RequiredWithoutParser::class);
