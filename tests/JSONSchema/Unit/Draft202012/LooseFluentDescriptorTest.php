<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(LooseFluentDescriptor::class), function (): void {
    it('can create a descriptor with schema', function (): void {
        $descriptor = LooseFluentDescriptor::create('https://json-schema.org/draft/2020-12/schema');

        expect($descriptor->compile())->toBe(
            [
                '$schema' => 'https://json-schema.org/draft/2020-12/schema',
            ],
        );
    });

    it('can create a descriptor without schema', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema();

        expect($descriptor->compile())->toBeEmpty();
    });

    it('can set type', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()->type('string');

        expect($descriptor->compile())->toBe(
            [
                'type' => 'string',
            ],
        );
    });

    it('can set type using Type class', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()->type(Type::string());

        expect($descriptor->compile())->toBe(
            [
                'type' => 'string',
            ],
        );
    });

    it('can set format', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type('string')
            ->format(StringFormat::DATE);

        expect($descriptor->compile())->toBe(
            [
                'type' => 'string',
                'format' => 'date',
            ],
        );
    });

    it('can set minimum and maximum', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type('number')
            ->minimum(0)
            ->maximum(100);

        expect($descriptor->compile())->toBe(
            [
                'type' => 'number',
                'maximum' => 100,
                'minimum' => 0,
            ],
        );
    });

    it('can set exclusive minimum and maximum', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type('number')
            ->exclusiveMinimum(0)
            ->exclusiveMaximum(100);

        expect($descriptor->compile())->toBe(
            [
                'type' => 'number',
                'exclusiveMaximum' => 100,
                'exclusiveMinimum' => 0,
            ],
        );
    });

    it('can set minLength and maxLength', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type('string')
            ->minLength(5)
            ->maxLength(10);

        expect($descriptor->compile())->toBe(
            [
                'type' => 'string',
                'maxLength' => 10,
                'minLength' => 5,
            ],
        );
    });

    it('can set pattern', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type('string')
            ->pattern('^[a-zA-Z0-9]*$');

        expect($descriptor->compile())->toBe(
            [
                'type' => 'string',
                'pattern' => '^[a-zA-Z0-9]*$',
            ],
        );
    });

    it('can set properties for object type', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type('object')
            ->properties(
                Property::create('name', LooseFluentDescriptor::withoutSchema()->type('string')),
                Property::create('age', LooseFluentDescriptor::withoutSchema()->type('integer')),
            );

        expect($descriptor->compile())->toBe(
            [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'type' => 'string',
                    ],
                    'age' => [
                        'type' => 'integer',
                    ],
                ],
            ],
        );
    });

    it('can set required properties', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type('object')
            ->properties(
                Property::create('name', LooseFluentDescriptor::withoutSchema()->type('string')),
                Property::create('age', LooseFluentDescriptor::withoutSchema()->type('integer')),
            )->required('name', 'age');

        expect($descriptor->compile())->toBe(
            [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'type' => 'string',
                    ],
                    'age' => [
                        'type' => 'integer',
                    ],
                ],
                'required' => [
                    'name',
                    'age',
                ],
            ],
        );
    });

    it('can set items for array type', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type(Type::array())
            ->items(LooseFluentDescriptor::withoutSchema()->type(Type::string()));

        expect($descriptor->compile())->toBe(
            [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
        );
    });

    it('can set enum values', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type('string')
            ->enum('red', 'green', 'blue');

        expect($descriptor->compile())->toBe(
            [
                'type' => 'string',
                'enum' => ['red', 'green', 'blue'],
            ],
        );
    });

    it('should return constant value as is', function (mixed $value): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()->const($value);

        expect($descriptor->compile())->toBe(
            [
                'const' => $value,
            ],
        );
    })->with([
        'test',
        1,
        true,
        null,
        false,
    ]);

    it('can set title, description and examples', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type('string')
            ->title('Color')
            ->description('A color name')
            ->examples('red', 'green', 'blue');

        expect($descriptor->compile())->toBe(
            [
                'title' => 'Color',
                'description' => 'A color name',
                'type' => 'string',
                'examples' => ['red', 'green', 'blue'],
            ],
        );
    });

    it('can set default value', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type('string')
            ->default('default-value');

        expect($descriptor->compile())->toBe(
            [
                'type' => 'string',
                'default' => 'default-value',
            ],
        );
    });

    it('can set readOnly and writeOnly', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type('string')
            ->readOnly()
            ->writeOnly();

        expect($descriptor->compile())->toBe(
            [
                'type' => 'string',
                'readOnly' => true,
                'writeOnly' => true,
            ],
        );
    });

    it('can set deprecated', function (): void {
        $descriptor = LooseFluentDescriptor::withoutSchema()
            ->type('string')
            ->deprecated();

        expect($descriptor->compile())->toBe(
            [
                'type' => 'string',
                'deprecated' => true,
            ],
        );
    });

    it('can be instantiated from array', function (array $payload): void {
        $descriptor = LooseFluentDescriptor::from($payload);

        expect($descriptor->compile())->toBe($payload);
    })->with([
        [
            [
                'type' => 'string',
                'format' => 'date',
                'maximum' => 100,
                'minimum' => 0,
            ],
        ],
    ]);
})->covers(LooseFluentDescriptor::class);
