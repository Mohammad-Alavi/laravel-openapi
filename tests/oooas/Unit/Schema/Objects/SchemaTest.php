<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Contracts\JSONSchema;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Formats\StringFormat;
use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\Keywords\Properties\Property;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Abstract\Factories\Components\SchemaFactory;
use MohammadAlavi\ObjectOrientedOpenAPI\Contracts\Interface\ShouldBeReferenced;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Formats\IntegerFormat;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Schema\Schema;

describe(class_basename(Schema::class), function (): void {
    it('can create array schema with all parameters', function (): void {
        $schema = Schema::array()
            ->title('Schema title')
            ->description('Schema description')
            ->default(['Earth'])
            ->items(Schema::string())
            ->maxItems(10)
            ->minItems(1)
            ->uniqueItems()
            ->readOnly()
            ->writeOnly()
            ->deprecated();

        expect(json_encode($schema))->toBe(
            json_encode(
                [
                    'title' => 'Schema title',
                    'description' => 'Schema description',
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                    'maxItems' => 10,
                    'minItems' => 1,
                    'uniqueItems' => true,
                    'deprecated' => true,
                    'readOnly' => true,
                    'writeOnly' => true,
                    'default' => ['Earth'],
                ],
            ),
        );
    });

    it('can create boolean schema with all parameters', function (): void {
        $schema = Schema::boolean()
            ->title('Schema title')
            ->description('Schema description')
            ->default(false)
            ->readOnly()
            ->writeOnly()
            ->deprecated();

        expect(json_encode($schema))->toBe(
            json_encode([
                'title' => 'Schema title',
                'description' => 'Schema description',
                'type' => 'boolean',
                'deprecated' => true,
                'readOnly' => true,
                'writeOnly' => true,
                'default' => false,
            ], ),
        );
    });

    it('can create integer schema with all parameters', function (): void {
        $schema = Schema::integer()
            ->title('Schema title')
            ->description('Schema description')
            ->default(false)
            ->format(IntegerFormat::INT32)
            ->maximum(100)
            ->exclusiveMaximum(101)
            ->minimum(1)
            ->exclusiveMinimum(0)
            ->multipleOf(2)
            ->readOnly()
            ->writeOnly();

        expect(json_encode($schema))->toBe(
            json_encode(
                [
                    'title' => 'Schema title',
                    'description' => 'Schema description',
                    'type' => 'integer',
                    'format' => 'int32',
                    'exclusiveMaximum' => 101,
                    'exclusiveMinimum' => 0,
                    'maximum' => 100,
                    'minimum' => 1,
                    'multipleOf' => 2,
                    'readOnly' => true,
                    'writeOnly' => true,
                    'default' => false,
                ],
            ),
        );
    });

    it('can create number schema with all parameters', function (): void {
        $schema = Schema::number()
            ->title('Schema title')
            ->description('Schema description')
            ->default(false)
            ->maximum(100)
            ->exclusiveMaximum(101)
            ->minimum(1)
            ->exclusiveMinimum(0)
            ->multipleOf(2)
            ->readOnly()
            ->writeOnly()
            ->deprecated();

        expect(json_encode($schema))->toBe(
            json_encode(
                [
                    'title' => 'Schema title',
                    'description' => 'Schema description',
                    'type' => 'number',
                    'exclusiveMaximum' => 101,
                    'exclusiveMinimum' => 0,
                    'maximum' => 100,
                    'minimum' => 1,
                    'multipleOf' => 2,
                    'deprecated' => true,
                    'readOnly' => true,
                    'writeOnly' => true,
                    'default' => false,
                ],
            ),
        );
    });

    it('can create object schema with all parameters', function (): void {
        $property = Schema::string()->format(StringFormat::UUID);

        $schema = Schema::object()
            ->title('Schema title')
            ->description('Schema description')
            ->default(false)
            ->required('id')
            ->properties(Property::create('id', $property))
            ->additionalProperties(Schema::integer())
            ->maxProperties(10)
            ->minProperties(1)
            ->readOnly()
            ->writeOnly()
            ->deprecated();

        expect(json_encode($schema))->toBe(
            json_encode(
                [
                    'title' => 'Schema title',
                    'description' => 'Schema description',
                    'type' => 'object',
                    'additionalProperties' => [
                        'type' => 'integer',
                    ],
                    'properties' => [
                        'id' => [
                            'type' => 'string',
                            'format' => 'uuid',
                        ],
                    ],
                    'maxProperties' => 10,
                    'minProperties' => 1,
                    'required' => ['id'],
                    'deprecated' => true,
                    'readOnly' => true,
                    'writeOnly' => true,
                    'default' => false,
                ],
            ),
        );
    });

    it('can create string schema with all parameters', function (): void {
        $schema = Schema::string()
            ->title('Schema title')
            ->description('Schema description')
            ->default('test')
            ->format(StringFormat::UUID)
            ->pattern('/[a-zA-Z]+/')
            ->maxLength(10)
            ->minLength(1)
            ->readOnly()
            ->writeOnly()
            ->deprecated();

        expect(json_encode($schema))->toBe(
            json_encode(
                [
                    'title' => 'Schema title',
                    'description' => 'Schema description',
                    'type' => 'string',
                    'format' => 'uuid',
                    'maxLength' => 10,
                    'minLength' => 1,
                    'pattern' => '/[a-zA-Z]+/',
                    'deprecated' => true,
                    'readOnly' => true,
                    'writeOnly' => true,
                    'default' => 'test',
                ],
            ),
        );
    });

    it('can create array schema with ref', function (): void {
        $reusableSchema = new class extends SchemaFactory implements ShouldBeReferenced {
            public function component(): JSONSchema
            {
                return Schema::object()
                    ->properties(
                        Property::create('name', Schema::string()),
                        Property::create('tag', Schema::string()),
                    )->required('name');
            }

            public static function name(): string
            {
                return 'test';
            }
        };
        $schema = Schema::array()->items($reusableSchema);

        expect(json_encode($schema))->toBe(
            json_encode(
                [
                    'type' => 'array',
                    'items' => [
                        '$ref' => '#/components/schemas/test',
                    ],
                ],
            ),
        );
    });

    it('can create schemas using methods', function ($method, $expectation): void {
        /** @var Schema $schema */
        $schema = Schema::$method($method);

        expect(json_encode($schema))->toBe(
            json_encode(
                [
                    'type' => $expectation,
                ],
            ),
        );
    })->with([
        'array' => ['array', 'array'],
        'boolean' => ['boolean', 'boolean'],
        'integer' => ['integer', 'integer'],
        'number' => ['number', 'number'],
        'object' => ['object', 'object'],
        'string' => ['string', 'string'],
    ]);
})->covers(Schema::class);
