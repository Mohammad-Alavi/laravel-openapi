<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Descriptor;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Type;

describe(class_basename(Descriptor::class), function (): void {
    it('can create a descriptor with schema', function (): void {
        $descriptor = Descriptor::create('https://json-schema.org/draft/2020-12/schema');

        expect($descriptor->jsonSerialize())->toBe([
            '$schema' => 'https://json-schema.org/draft/2020-12/schema',
        ]);
    });

    it('can create a descriptor without schema', function (): void {
        $descriptor = Descriptor::withoutSchema();

        expect($descriptor->jsonSerialize())->toBe([]);
    });

    it('can set type', function (): void {
        $descriptor = Descriptor::withoutSchema()->type('string');

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'string',
        ]);
    });

    it('can set type using Type class', function (): void {
        $descriptor = Descriptor::withoutSchema()->type(Type::string());

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'string',
        ]);
    });

    it('can set format', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('string')
            ->format(StringFormat::DATE);

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'string',
            'format' => 'date',
        ]);
    });

    it('can set minimum and maximum', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('number')
            ->minimum(0)
            ->maximum(100);

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'number',
            'maximum' => 100.0,
            'minimum' => 0.0,
        ]);
    });

    it('can set exclusive minimum and maximum', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('number')
            ->exclusiveMinimum(0)
            ->exclusiveMaximum(100);

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'number',
            'exclusiveMaximum' => 100.0,
            'exclusiveMinimum' => 0.0,
        ]);
    });

    it('can set minLength and maxLength', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('string')
            ->minLength(5)
            ->maxLength(10);

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'string',
            'maxLength' => 10,
            'minLength' => 5,
        ]);
    });

    it('can set pattern', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('string')
            ->pattern('^[a-zA-Z0-9]*$');

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'string',
            'pattern' => '^[a-zA-Z0-9]*$',
        ]);
    });

    it('can set properties for object type', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('object')
            ->properties(
                Property::create('name', Descriptor::withoutSchema()->type('string')),
                Property::create('age', Descriptor::withoutSchema()->type('integer')),
            );

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'object',
            'properties' => [
                'name' => [
                    'type' => 'string',
                ],
                'age' => [
                    'type' => 'integer',
                ],
            ],
        ]);
    });

    it('can set required properties', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('object')
            ->properties(
                Property::create('name', Descriptor::withoutSchema()->type('string')),
                Property::create('age', Descriptor::withoutSchema()->type('integer')),
            )->required('name', 'age');

        expect($descriptor->jsonSerialize())->toBe([
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
        ]);
    });

    it('can set items for array type', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type(Type::array())
            ->items(Descriptor::withoutSchema()->type(Type::string()));

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'array',
            'items' => [
                'type' => 'string',
            ],
        ]);
    });

    it('can set enum values', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('string')
            ->enum('red', 'green', 'blue');

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'string',
            'enum' => ['red', 'green', 'blue'],
        ]);
    });

    it('can set const value', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('string')
            ->const('fixed-value');

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'string',
            'const' => 'fixed-value',
        ]);
    });

    it('can set title, description and examples', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('string')
            ->title('Color')
            ->description('A color name')
            ->examples('red', 'green', 'blue');

        expect($descriptor->jsonSerialize())->toBe([
            'title' => 'Color',
            'description' => 'A color name',
            'type' => 'string',
            'examples' => ['red', 'green', 'blue'],
        ]);
    });

    it('can set default value', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('string')
            ->default('default-value');

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'string',
            'default' => 'default-value',
        ]);
    });

    it('can set readOnly and writeOnly', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('string')
            ->readOnly(true)
            ->writeOnly(false);

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'string',
            'readOnly' => true,
            'writeOnly' => false,
        ]);
    });

    it('can set deprecated', function (): void {
        $descriptor = Descriptor::withoutSchema()
            ->type('string')
            ->deprecated(true);

        expect($descriptor->jsonSerialize())->toBe([
            'type' => 'string',
            'deprecated' => true,
        ]);
    });
})->covers(Descriptor::class);
