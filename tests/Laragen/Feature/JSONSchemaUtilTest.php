<?php

use LaravelRulesToSchema\Facades\LaravelRulesToSchema;
use MohammadAlavi\Laragen\Support\JSONSchemaUtil;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(JSONSchemaUtil::class), function (): void {
    it('can be instantiated from array', function (array $rules, array $expectation): void {
        $descriptor = LooseFluentDescriptor::from(LaravelRulesToSchema::parse($rules)->compile());

        expect(\Safe\json_encode($descriptor))->toBe(
            \Safe\json_encode($expectation),
        );
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
                'age' => ['nullable', 'integer', 'between:18,99'],
            ],
            [
                'type' => 'object',
                'properties' => [
                    'age' => [
                        'type' => ['null', 'integer'],
                        // TODO: Implement support for 'between' rule.
                        // 'minimum' => 18,
                        // 'maximum' => 99,
                    ],
                ],
            ],
        ],
    ]);
})->covers(JSONSchemaUtil::class);
