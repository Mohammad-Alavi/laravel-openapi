<?php

use MohammadAlavi\Laragen\RuleParsers\RequiredWith;
use MohammadAlavi\Laragen\Support\RulesToSchema;

describe(class_basename(RequiredWith::class), function (): void {
    $anyOf = [
        [
            'properties' => [
                'name' => [
                    'type' => 'string',
                    'maxLength' => 20,
                    'minLength' => 3,
                ],
            ],
            'required' => ['name'],
        ],
        [
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'format' => 'email',
                ],
            ],
            'required' => ['email'],
        ],
    ];

    it(
        'can parse required_with rules',
        function (array $rules, array $expectation): void {
            expect(json_encode(app(RulesToSchema::class)->parse($rules)->compile(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
                ->toBe(json_encode($expectation, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        },
    )->with([
        [
            [
                'name' => ['required_with:email', 'string', 'max:20', 'min:3'],
                'email' => ['required_with:name', 'email'],
            ],
            [
                'type' => 'object',
                'anyOf' => $anyOf,
            ],
        ],
        [
            [
                'name' => ['required_with:email', 'string', 'max:20', 'min:3'],
                'email' => ['required_with:name', 'email'],
                'age' => ['required_with:name,email', 'nullable', 'integer'],
            ],
            [
                'type' => 'object',
                'anyOf' => [
                    ...$anyOf,
                    [
                        'properties' => [
                            'age' => [
                                'type' => ['null', 'integer'],
                            ],
                        ],
                        'required' => ['age'],
                    ],
                ],
            ],
        ],
        [
            [
                'name' => ['required_with:email', 'string', 'max:20', 'min:3'],
                'email' => ['required_with:name', 'email'],
                'age' => ['nullable', 'integer'],
            ],
            [
                'type' => 'object',
                'allOf' => [
                    'anyOf' => $anyOf,
                    [
                        'properties' => [
                            'age' => [
                                'type' => ['null', 'integer'],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            [
                'password' => 'string|min:8|confirmed|required',
                'address' => ['required', 'string', 'max:255'],
                'name' => ['required_with:email', 'string', 'max:20', 'min:3'],
                'email' => ['required_with:name', 'email'],
                'age' => ['nullable', 'integer'],
            ],
            [
                'type' => 'object',
                'allOf' => [
                    'anyOf' => $anyOf,
                    [
                        'properties' => [
                            'password' => [
                                'type' => 'string',
                                'minLength' => 8,
                            ],
                        ],
                    ],
                    [
                        'properties' => [
                            'password_confirmed' => [
                                'type' => 'string',
                                'minLength' => 8,
                            ],
                        ],
                    ],
                    [
                        'properties' => [
                            'address' => [
                                'type' => 'string',
                                'maxLength' => 255,
                            ],
                        ],
                    ],
                    [
                        'properties' => [
                            'age' => [
                                'type' => ['null', 'integer'],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            [
                'age' => ['nullable', 'integer'],
            ],
            [
                'type' => 'object',
                'properties' => [
                    'age' => [
                        'type' => ['null', 'integer'],
                    ],
                ],
            ],
        ],
    ]);
})->covers(RequiredWith::class)->only();
