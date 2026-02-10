<?php

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use MohammadAlavi\Laragen\Laragen;
use MohammadAlavi\LaravelRulesToSchema\Parsers\RequiredWithoutParser;
use MohammadAlavi\LaravelRulesToSchema\Parsers\RequiredWithParser;
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

    $nameProperty = [
        'name' => [
            'type' => 'string',
            'maxLength' => 20,
            'minLength' => 3,
        ],
    ];

    $emailProperty = [
        'email' => [
            'type' => 'string',
            'format' => 'email',
        ],
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

    $ifEmailThenName = ['if' => ['required' => ['email']], 'then' => ['required' => ['name']]];
    $ifNameThenEmail = ['if' => ['required' => ['name']], 'then' => ['required' => ['email']]];
    $ifNameOrEmailThenAge = [
        'if' => ['anyOf' => [['required' => ['name']], ['required' => ['email']]]],
        'then' => ['required' => ['age']],
    ];

    it(
        'can parse required_with rules',
        function (Route $route, array $expectation): void {
            config(['laragen.autogen.example' => false]);

            $compiled = Laragen::extractRequestBodySchema($route)->compile();
            expect($compiled)->toBe($expectation);
        },
    )->with([
        'justTwoRequireWithRules' => [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'justTwoRequireWithRules']),
            [
                'allOf' => [
                    $ifEmailThenName,
                    $ifNameThenEmail,
                ],
                'type' => 'object',
                'properties' => [
                    ...$nameProperty,
                    ...$emailProperty,
                ],
            ],
        ],
        'justThreeRequireWithRules' => [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'justThreeRequireWithRules']),
            [
                'allOf' => [
                    $ifEmailThenName,
                    $ifNameThenEmail,
                    $ifNameOrEmailThenAge,
                ],
                'type' => 'object',
                'properties' => [
                    ...$nameProperty,
                    ...$emailProperty,
                    ...$age,
                ],
            ],
        ],
        'withAfterRule' => [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'withAfterRule']),
            [
                'allOf' => [
                    $ifEmailThenName,
                    $ifNameThenEmail,
                ],
                'type' => 'object',
                'properties' => [
                    ...$nameProperty,
                    ...$emailProperty,
                    ...$age,
                ],
            ],
        ],
        'withBeforeAfterRules' => [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'withBeforeAfterRules']),
            [
                'allOf' => [
                    $ifEmailThenName,
                    $ifNameThenEmail,
                ],
                'type' => 'object',
                'properties' => [
                    ...$password,
                    ...$passwordConfirmed,
                    ...$address,
                    ...$nameProperty,
                    ...$emailProperty,
                    ...$age,
                ],
                'required' => ['password', 'password_confirmed', 'address'],
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
                'allOf' => [
                    $ifNameThenEmail,
                    $ifEmailThenName,
                ],
                'type' => 'object',
                'properties' => [
                    ...$password,
                    ...$passwordConfirmed,
                    ...$emailProperty,
                    ...$address,
                    ...$nameProperty,
                    ...$age,
                ],
                'required' => ['password', 'password_confirmed', 'address'],
            ],
        ],
        'anotherWithMixedOrderRulesRequest' => [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'anotherWithMixedOrderRulesRequest']),
            [
                'allOf' => [
                    $ifEmailThenName,
                    $ifNameThenEmail,
                ],
                'type' => 'object',
                'properties' => [
                    ...$password,
                    ...$passwordConfirmed,
                    ...$nameProperty,
                    ...$age,
                    ...$emailProperty,
                    ...$address,
                ],
                'required' => ['password', 'password_confirmed', 'address'],
            ],
        ],
        'withEverythingMixedRulesRequest' => [
            fn (): Route => RouteFacade::get('test', [RequireWithController::class, 'withEverythingMixedRulesRequest']),
            [
                'allOf' => [
                    $ifNameThenEmail,
                    $ifEmailThenName,
                ],
                'type' => 'object',
                'properties' => [
                    ...$password,
                    ...$passwordConfirmed,
                    ...$emailProperty,
                    ...$address,
                    ...$nameProperty,
                    ...$age,
                ],
                'required' => ['password', 'password_confirmed', 'address'],
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
})->covers(Laragen::class, RequiredWithParser::class, RequiredWithoutParser::class);
