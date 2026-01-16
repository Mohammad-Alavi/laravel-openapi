<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ArrayRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\BooleanRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ConstantRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\EnumRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\IntegerRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\NullRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\NumberRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\ObjectRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\Restrictors\StringRestrictor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\StrictFluentDescriptor;

describe(class_basename(StrictFluentDescriptor::class), function (): void {
    describe('type-safe factory methods', function (): void {
        it('returns NullRestrictor for null()', function (): void {
            $descriptor = StrictFluentDescriptor::null();

            expect($descriptor)->toBeInstanceOf(NullRestrictor::class);
            expect($descriptor->compile())->toBe(['type' => 'null']);
        });

        it('returns BooleanRestrictor for boolean()', function (): void {
            $descriptor = StrictFluentDescriptor::boolean();

            expect($descriptor)->toBeInstanceOf(BooleanRestrictor::class);
            expect($descriptor->compile())->toBe(['type' => 'boolean']);
        });

        it('returns StringRestrictor for string()', function (): void {
            $descriptor = StrictFluentDescriptor::string();

            expect($descriptor)->toBeInstanceOf(StringRestrictor::class);
            expect($descriptor->compile())->toBe(['type' => 'string']);
        });

        it('returns IntegerRestrictor for integer()', function (): void {
            $descriptor = StrictFluentDescriptor::integer();

            expect($descriptor)->toBeInstanceOf(IntegerRestrictor::class);
            expect($descriptor->compile())->toBe(['type' => 'integer']);
        });

        it('returns NumberRestrictor for number()', function (): void {
            $descriptor = StrictFluentDescriptor::number();

            expect($descriptor)->toBeInstanceOf(NumberRestrictor::class);
            expect($descriptor->compile())->toBe(['type' => 'number']);
        });

        it('returns ObjectRestrictor for object()', function (): void {
            $descriptor = StrictFluentDescriptor::object();

            expect($descriptor)->toBeInstanceOf(ObjectRestrictor::class);
            expect($descriptor->compile())->toBe(['type' => 'object']);
        });

        it('returns ArrayRestrictor for array()', function (): void {
            $descriptor = StrictFluentDescriptor::array();

            expect($descriptor)->toBeInstanceOf(ArrayRestrictor::class);
            expect($descriptor->compile())->toBe(['type' => 'array']);
        });

        it('returns ConstantRestrictor for constant()', function (): void {
            $descriptor = StrictFluentDescriptor::constant('fixed-value');

            expect($descriptor)->toBeInstanceOf(ConstantRestrictor::class);
            expect($descriptor->compile())->toBe(['const' => 'fixed-value']);
        });

        it('returns EnumRestrictor for enumerator()', function (): void {
            $descriptor = StrictFluentDescriptor::enumerator('a', 'b', 'c');

            expect($descriptor)->toBeInstanceOf(EnumRestrictor::class);
            expect($descriptor->compile())->toBe(['enum' => ['a', 'b', 'c']]);
        });
    });

    describe('string schema building', function (): void {
        it('can add minLength to string', function (): void {
            $descriptor = StrictFluentDescriptor::string()->minLength(1);

            expect($descriptor->compile())->toBe([
                'type' => 'string',
                'minLength' => 1,
            ]);
        });

        it('can add maxLength to string', function (): void {
            $descriptor = StrictFluentDescriptor::string()->maxLength(100);

            expect($descriptor->compile())->toBe([
                'type' => 'string',
                'maxLength' => 100,
            ]);
        });

        it('can add pattern to string', function (): void {
            $descriptor = StrictFluentDescriptor::string()->pattern('^[a-z]+$');

            expect($descriptor->compile())->toBe([
                'type' => 'string',
                'pattern' => '^[a-z]+$',
            ]);
        });

        it('can add format to string', function (): void {
            $descriptor = StrictFluentDescriptor::string()->format(StringFormat::EMAIL);

            expect($descriptor->compile())->toBe([
                'type' => 'string',
                'format' => 'email',
            ]);
        });

        it('can chain multiple string constraints', function (): void {
            $descriptor = StrictFluentDescriptor::string()
                ->minLength(5)
                ->maxLength(50)
                ->pattern('^[A-Z]')
                ->format(StringFormat::URI);

            expect($descriptor->compile())->toBe([
                'type' => 'string',
                'format' => 'uri',
                'maxLength' => 50,
                'minLength' => 5,
                'pattern' => '^[A-Z]',
            ]);
        });
    });

    describe('number/integer schema building', function (): void {
        it('can add minimum to integer', function (): void {
            $descriptor = StrictFluentDescriptor::integer()->minimum(0);

            expect($descriptor->compile())->toBe([
                'type' => 'integer',
                'minimum' => 0,
            ]);
        });

        it('can add maximum to number', function (): void {
            $descriptor = StrictFluentDescriptor::number()->maximum(100.5);

            expect($descriptor->compile())->toBe([
                'type' => 'number',
                'maximum' => 100.5,
            ]);
        });

        it('can add exclusiveMinimum and exclusiveMaximum', function (): void {
            $descriptor = StrictFluentDescriptor::number()
                ->exclusiveMinimum(0)
                ->exclusiveMaximum(100);

            expect($descriptor->compile())->toBe([
                'type' => 'number',
                'exclusiveMaximum' => 100,
                'exclusiveMinimum' => 0,
            ]);
        });

        it('can add multipleOf', function (): void {
            $descriptor = StrictFluentDescriptor::integer()->multipleOf(5);

            expect($descriptor->compile())->toBe([
                'type' => 'integer',
                'multipleOf' => 5,
            ]);
        });
    });

    describe('array schema building', function (): void {
        it('can add items to array', function (): void {
            $descriptor = StrictFluentDescriptor::array()
                ->items(StrictFluentDescriptor::string());

            expect($descriptor->compile())->toBe([
                'type' => 'array',
                'items' => ['type' => 'string'],
            ]);
        });

        it('can add minItems and maxItems', function (): void {
            $descriptor = StrictFluentDescriptor::array()
                ->minItems(1)
                ->maxItems(10);

            expect($descriptor->compile())->toBe([
                'type' => 'array',
                'maxItems' => 10,
                'minItems' => 1,
            ]);
        });

        it('can add uniqueItems', function (): void {
            $descriptor = StrictFluentDescriptor::array()->uniqueItems();

            expect($descriptor->compile())->toBe([
                'type' => 'array',
                'uniqueItems' => true,
            ]);
        });

        it('can add prefixItems', function (): void {
            $descriptor = StrictFluentDescriptor::array()
                ->prefixItems(
                    StrictFluentDescriptor::string(),
                    StrictFluentDescriptor::integer(),
                );

            expect($descriptor->compile())->toBe([
                'type' => 'array',
                'prefixItems' => [
                    ['type' => 'string'],
                    ['type' => 'integer'],
                ],
            ]);
        });

        it('can add contains', function (): void {
            $descriptor = StrictFluentDescriptor::array()
                ->contains(StrictFluentDescriptor::string()->const('special'));

            expect($descriptor->compile())->toBe([
                'type' => 'array',
                'contains' => [
                    'type' => 'string',
                    'const' => 'special',
                ],
            ]);
        });
    });

    describe('object schema building', function (): void {
        it('can add properties to object', function (): void {
            $descriptor = StrictFluentDescriptor::object()
                ->properties(
                    Property::create('name', StrictFluentDescriptor::string()),
                    Property::create('age', StrictFluentDescriptor::integer()),
                );

            expect($descriptor->compile())->toBe([
                'type' => 'object',
                'properties' => [
                    'name' => ['type' => 'string'],
                    'age' => ['type' => 'integer'],
                ],
            ]);
        });

        it('can add required properties', function (): void {
            $descriptor = StrictFluentDescriptor::object()
                ->properties(
                    Property::create('id', StrictFluentDescriptor::string()),
                )
                ->required('id');

            expect($descriptor->compile())->toBe([
                'type' => 'object',
                'properties' => [
                    'id' => ['type' => 'string'],
                ],
                'required' => ['id'],
            ]);
        });

        it('can add additionalProperties false', function (): void {
            $descriptor = StrictFluentDescriptor::object()
                ->additionalProperties(false);

            expect($descriptor->compile())->toBe([
                'type' => 'object',
                'additionalProperties' => false,
            ]);
        });

        it('can add additionalProperties schema', function (): void {
            $descriptor = StrictFluentDescriptor::object()
                ->additionalProperties(StrictFluentDescriptor::string());

            expect($descriptor->compile())->toBe([
                'type' => 'object',
                'additionalProperties' => ['type' => 'string'],
            ]);
        });

        it('can add minProperties and maxProperties', function (): void {
            $descriptor = StrictFluentDescriptor::object()
                ->minProperties(1)
                ->maxProperties(10);

            expect($descriptor->compile())->toBe([
                'type' => 'object',
                'maxProperties' => 10,
                'minProperties' => 1,
            ]);
        });
    });

    describe('metadata and annotations', function (): void {
        it('can add title and description', function (): void {
            $descriptor = StrictFluentDescriptor::string()
                ->title('Username')
                ->description('The unique username for the account');

            expect($descriptor->compile())->toBe([
                'title' => 'Username',
                'description' => 'The unique username for the account',
                'type' => 'string',
            ]);
        });

        it('can add default value', function (): void {
            $descriptor = StrictFluentDescriptor::string()->default('unknown');

            expect($descriptor->compile())->toBe([
                'type' => 'string',
                'default' => 'unknown',
            ]);
        });

        it('can add examples', function (): void {
            $descriptor = StrictFluentDescriptor::string()
                ->examples('john_doe', 'jane_smith');

            expect($descriptor->compile())->toBe([
                'type' => 'string',
                'examples' => ['john_doe', 'jane_smith'],
            ]);
        });

        it('can add deprecated', function (): void {
            $descriptor = StrictFluentDescriptor::string()->deprecated();

            expect($descriptor->compile())->toBe([
                'type' => 'string',
                'deprecated' => true,
            ]);
        });

        it('can add readOnly', function (): void {
            $descriptor = StrictFluentDescriptor::string()->readOnly();

            expect($descriptor->compile())->toBe([
                'type' => 'string',
                'readOnly' => true,
            ]);
        });

        it('can add writeOnly', function (): void {
            $descriptor = StrictFluentDescriptor::string()->writeOnly();

            expect($descriptor->compile())->toBe([
                'type' => 'string',
                'writeOnly' => true,
            ]);
        });
    });

    describe('composition keywords', function (): void {
        it('can add allOf', function (): void {
            $descriptor = StrictFluentDescriptor::object()
                ->allOf(
                    StrictFluentDescriptor::object()->required('name'),
                    StrictFluentDescriptor::object()->required('email'),
                );

            $compiled = $descriptor->compile();

            expect($compiled['type'])->toBe('object');
            expect($compiled['allOf'])->toHaveCount(2);
        });

        it('can add anyOf', function (): void {
            $descriptor = StrictFluentDescriptor::string()
                ->anyOf(
                    StrictFluentDescriptor::string()->format(StringFormat::EMAIL),
                    StrictFluentDescriptor::string()->format(StringFormat::URI),
                );

            $compiled = $descriptor->compile();

            expect($compiled['anyOf'])->toHaveCount(2);
        });

        it('can add oneOf', function (): void {
            $descriptor = StrictFluentDescriptor::integer()
                ->oneOf(
                    StrictFluentDescriptor::integer()->multipleOf(3),
                    StrictFluentDescriptor::integer()->multipleOf(5),
                );

            $compiled = $descriptor->compile();

            expect($compiled['oneOf'])->toHaveCount(2);
        });

        it('can add not', function (): void {
            $descriptor = StrictFluentDescriptor::string()
                ->not(StrictFluentDescriptor::string()->const('forbidden'));

            $compiled = $descriptor->compile();

            expect($compiled['not'])->toBe([
                'type' => 'string',
                'const' => 'forbidden',
            ]);
        });
    });

    describe('conditional keywords', function (): void {
        it('can add if/then/else', function (): void {
            $descriptor = StrictFluentDescriptor::object()
                ->if(
                    StrictFluentDescriptor::object()->properties(
                        Property::create('country', StrictFluentDescriptor::constant('USA')),
                    ),
                )
                ->then(
                    StrictFluentDescriptor::object()->required('zip_code'),
                )
                ->else(
                    StrictFluentDescriptor::object()->required('postal_code'),
                );

            $compiled = $descriptor->compile();

            expect($compiled)->toHaveKey('if');
            expect($compiled)->toHaveKey('then');
            expect($compiled)->toHaveKey('else');
        });
    });

    describe('complex schema building', function (): void {
        it('can build a complete user schema', function (): void {
            $descriptor = StrictFluentDescriptor::object()
                ->title('User')
                ->description('A user in the system')
                ->properties(
                    Property::create('id', StrictFluentDescriptor::string()->format(StringFormat::UUID)->readOnly()),
                    Property::create('name', StrictFluentDescriptor::string()->minLength(1)->maxLength(100)),
                    Property::create('email', StrictFluentDescriptor::string()->format(StringFormat::EMAIL)),
                    Property::create('age', StrictFluentDescriptor::integer()->minimum(0)->maximum(150)),
                    Property::create('tags', StrictFluentDescriptor::array()->items(StrictFluentDescriptor::string())->uniqueItems()),
                )
                ->required('id', 'name', 'email')
                ->additionalProperties(false);

            $compiled = $descriptor->compile();

            expect($compiled['title'])->toBe('User');
            expect($compiled['type'])->toBe('object');
            expect($compiled['required'])->toBe(['id', 'name', 'email']);
            expect($compiled['additionalProperties'])->toBeFalse();
            expect($compiled['properties'])->toHaveKeys(['id', 'name', 'email', 'age', 'tags']);
        });
    });
})->covers(StrictFluentDescriptor::class);
