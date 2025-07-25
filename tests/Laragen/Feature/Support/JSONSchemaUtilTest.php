<?php

use MohammadAlavi\Laragen\Support\JSONSchemaUtil;

describe(class_basename(JSONSchemaUtil::class), function (): void {
    it('can be instantiated from array', function (array $rules, array $expectation): void {
        $descriptor = JSONSchemaUtil::fromRequestRules($rules);

        expect($descriptor->toArray())->toBe($expectation);
    })->with([
        [
            [
                // TODO: Implement support for required_without rules using JSON Schema `anyOf` or `oneOf`.
                'name' => ['required_without:email', 'string', 'max:255'],
                'email' => 'required_without:name|email|max:255',
            ],
            [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'type' => 'string',
                        'maxLength' => 255,
                    ],
                    'email' => [
                        'type' => 'string',
                        'format' => 'email',
                        'maxLength' => 255,
                    ],
                ],
            ],
        ],
        [
            [
                'password' => 'string|min:8|confirmed',
            ],
            [
                'type' => 'object',
                'properties' => [
                    'password' => [
                        'type' => 'string',
                        'minLength' => 8,
                    ],
                    'password_confirmed' => [
                        'type' => 'string',
                        'minLength' => 8,
                    ],
                ],
            ],
        ],
        [
            [
                'age' => ['nullable', 'integer', 'between:18,99', 'min:3', 'max:10'],
            ],
            [
                'type' => 'object',
                'properties' => [
                    'age' => [
                        'type' => ['null', 'integer'],
                        'maximum' => 10,
                        'minimum' => 3,
                        // TODO: Implement support for 'between' rule.
                        // 'minimum' => 18,
                        // 'maximum' => 99,
                    ],
                ],
            ],
        ],
        [
            [
                'value' => [
                    'string',
                    'min:3',
                    'max:10',
                    'between:4,8',
                    'in:foo,bar',
                    'regex:/[A-Z]+/',
                ],
            ],
            [
                'type' => 'object',
                'properties' => [
                    'value' => [
                        'type' => 'string',
                        'maxLength' => 10,
                        'minLength' => 3,
                        // TODO: Implement support for 'enum' rule.
                        // 'enum' => ['foo', 'bar'],
                        'pattern' => '[A-Z]+',
                    ],
                ],
            ],
        ],
    ]);
})->covers(JSONSchemaUtil::class);
