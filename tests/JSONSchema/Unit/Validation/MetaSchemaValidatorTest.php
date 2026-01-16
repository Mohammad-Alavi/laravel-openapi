<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Validation\MetaSchemaValidationError;
use MohammadAlavi\ObjectOrientedJSONSchema\Validation\MetaSchemaValidationResult;
use MohammadAlavi\ObjectOrientedJSONSchema\Validation\MetaSchemaValidator;

describe(class_basename(MetaSchemaValidator::class), function (): void {
    describe('valid schemas', function (): void {
        it('validates a simple string schema', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = StrictFluentDescriptor::string()
                ->minLength(1)
                ->maxLength(100)
                ->compile();

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeTrue();
            expect($result->hasErrors())->toBeFalse();
        });

        it('validates a complex object schema', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = StrictFluentDescriptor::object()
                ->properties(
                    MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property::create(
                        'name',
                        StrictFluentDescriptor::string(),
                    ),
                    MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property::create(
                        'age',
                        StrictFluentDescriptor::integer()->minimum(0),
                    ),
                )
                ->required('name')
                ->compile();

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeTrue();
        });

        it('validates allOf schema', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = [
                'allOf' => [
                    ['type' => 'object'],
                    ['required' => ['name']],
                ],
            ];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeTrue();
        });

        it('validates boolean schema values', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = [
                'type' => 'object',
                'additionalProperties' => false,
            ];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeTrue();
        });
    });

    describe('type keyword validation', function (): void {
        it('rejects invalid type string', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['type' => 'stringg'];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
            expect($result->errors[0]->code)->toBe(MetaSchemaValidationError::INVALID_VALUE);
            expect($result->errors[0]->path)->toBe('type');
        });

        it('accepts valid type array', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['type' => ['string', 'null']];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeTrue();
        });

        it('rejects invalid type in array', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['type' => ['string', 'invalid']];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
            expect($result->errors[0]->code)->toBe(MetaSchemaValidationError::INVALID_VALUE);
        });

        it('rejects non-string type value', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['type' => 123];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
            expect($result->errors[0]->code)->toBe(MetaSchemaValidationError::INVALID_TYPE);
        });
    });

    describe('numeric keyword validation', function (): void {
        it('rejects non-integer maxLength', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['maxLength' => 'ten'];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
            expect($result->errors[0]->message)->toContain('must be an integer');
        });

        it('rejects negative maxLength', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['maxLength' => -1];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
            expect($result->errors[0]->message)->toContain('non-negative');
        });

        it('rejects non-number minimum', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['minimum' => '10'];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
            expect($result->errors[0]->message)->toContain('must be a number');
        });

        it('rejects non-positive multipleOf', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['multipleOf' => 0];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
            expect($result->errors[0]->message)->toContain('greater than 0');
        });

        it('rejects negative multipleOf', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['multipleOf' => -5];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
        });
    });

    describe('boolean keyword validation', function (): void {
        it('rejects non-boolean uniqueItems', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['uniqueItems' => 'yes'];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
            expect($result->errors[0]->message)->toContain('must be a boolean');
        });

        it('rejects non-boolean deprecated', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['deprecated' => 1];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
        });
    });

    describe('array keyword validation', function (): void {
        it('rejects non-array required', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['required' => 'name'];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
            expect($result->errors[0]->message)->toContain('array of strings');
        });

        it('rejects non-string items in required', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['required' => ['name', 123]];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
        });

        it('rejects empty allOf', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['allOf' => []];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
            expect($result->errors[0]->message)->toContain('non-empty');
        });

        it('rejects non-schema items in allOf', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['allOf' => ['not a schema']];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
        });
    });

    describe('object keyword validation', function (): void {
        it('rejects non-object properties', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['properties' => 'invalid'];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
        });

        it('validates nested schemas in properties', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = [
                'properties' => [
                    'name' => ['type' => 'invalid_type'],
                ],
            ];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
            expect($result->errors[0]->path)->toBe('properties/name/type');
        });
    });

    describe('core keyword validation', function (): void {
        it('rejects non-string $id', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['$id' => 123];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
            expect($result->errors[0]->message)->toContain('must be a string');
        });

        it('rejects non-object $vocabulary', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = ['$vocabulary' => 'invalid'];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
        });

        it('rejects non-boolean $vocabulary values', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = [
                '$vocabulary' => [
                    'https://example.com/vocab' => 'yes',
                ],
            ];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
        });
    });

    describe('deeply nested validation', function (): void {
        it('validates deeply nested schemas', function (): void {
            $validator = new MetaSchemaValidator();
            $schema = [
                'allOf' => [
                    [
                        'properties' => [
                            'data' => [
                                'items' => [
                                    'type' => 'invalid',
                                ],
                            ],
                        ],
                    ],
                ],
            ];

            $result = $validator->validate($schema);

            expect($result->isValid())->toBeFalse();
            expect($result->errors[0]->path)->toBe('allOf[0]/properties/data/items/type');
        });
    });
})->covers(MetaSchemaValidator::class);

describe(class_basename(MetaSchemaValidationResult::class), function (): void {
    it('reports valid state correctly', function (): void {
        $result = new MetaSchemaValidationResult(true);

        expect($result->isValid())->toBeTrue();
        expect($result->hasErrors())->toBeFalse();
        expect($result->getErrorMessages())->toBeEmpty();
    });

    it('reports invalid state correctly', function (): void {
        $error = new MetaSchemaValidationError('Test error', 'path', 'code');
        $result = new MetaSchemaValidationResult(false, [$error]);

        expect($result->isValid())->toBeFalse();
        expect($result->hasErrors())->toBeTrue();
        expect($result->getErrorMessages())->toBe(['Test error']);
    });

    it('groups errors by path', function (): void {
        $error1 = new MetaSchemaValidationError('Error 1', 'path/a', 'code');
        $error2 = new MetaSchemaValidationError('Error 2', 'path/a', 'code');
        $error3 = new MetaSchemaValidationError('Error 3', 'path/b', 'code');
        $result = new MetaSchemaValidationResult(false, [$error1, $error2, $error3]);

        $grouped = $result->getErrorsByPath();

        expect($grouped)->toHaveKey('path/a');
        expect($grouped)->toHaveKey('path/b');
        expect($grouped['path/a'])->toHaveCount(2);
        expect($grouped['path/b'])->toHaveCount(1);
    });
})->covers(MetaSchemaValidationResult::class);

describe(class_basename(MetaSchemaValidationError::class), function (): void {
    it('has correct properties', function (): void {
        $error = new MetaSchemaValidationError('message', 'path', MetaSchemaValidationError::INVALID_TYPE);

        expect($error->message)->toBe('message');
        expect($error->path)->toBe('path');
        expect($error->code)->toBe(MetaSchemaValidationError::INVALID_TYPE);
    });

    it('converts to string', function (): void {
        $error = new MetaSchemaValidationError('Test message', 'test/path', MetaSchemaValidationError::INVALID_VALUE);

        expect((string) $error)->toBe('[invalid_value] test/path: Test message');
    });
})->covers(MetaSchemaValidationError::class);
