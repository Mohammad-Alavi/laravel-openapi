<?php

// use MohammadAlavi\LaravelOpenApi\Contracts\Abstract\Factories\Components\ReusableSchemaFactory;
// use MohammadAlavi\ObjectOrientedJSONSchema\Trash\Descriptor;
// use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Discriminator\Discriminator;
// use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\ExternalDocs\ExternalDocs;
// use MohammadAlavi\ObjectOrientedJSONSchema\v31\Schema;
// use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\XML\Xml;
//
// describe(class_basename(Schema::class), function (): void {
//    it('can create array schema with all parameters', function (): void {
//        $schema = Schema::create('test')
//            ->title('Schema title')
//            ->description('Schema description')
//            ->enum(['Earth'], ['Venus'], ['Mars'])
//            ->default(['Earth'])
//            ->type(Schema::TYPE_ARRAY)
//            ->items(Schema::string('test'))
//            ->maxItems(10)
//            ->minItems(1)
//            ->uniqueItems()
//            ->nullable()
//            ->discriminator(Discriminator::create()->propertyName('Property name'))
//            ->readOnly()
//            ->writeOnly()
//            ->xml(Xml::create())
//            ->externalDocs(ExternalDocs::create()->url('https://example.com'))
//            ->example(['Venus'])
//            ->deprecated();
//
//        expect($schema->asArray())->toBe([
//            'title' => 'Schema title',
//            'description' => 'Schema description',
//            'enum' => [
//                ['Earth'],
//                ['Venus'],
//                ['Mars'],
//            ],
//            'default' => ['Earth'],
//            'type' => 'array',
//            'items' => ['type' => 'string'],
//            'maxItems' => 10,
//            'minItems' => 1,
//            'uniqueItems' => true,
//            'nullable' => true,
//            'discriminator' => ['propertyName' => 'Property name'],
//            'readOnly' => true,
//            'writeOnly' => true,
//            'xml' => [],
//            'externalDocs' => ['url' => 'https://example.com'],
//            'example' => ['Venus'],
//            'deprecated' => true,
//        ]);
//    });
//
//    it('can create boolean schema with all parameters', function (): void {
//        $schema = Schema::create('test')
//            ->title('Schema title')
//            ->description('Schema description')
//            ->default(false)
//            ->type(Schema::TYPE_BOOLEAN)
//            ->nullable()
//            ->discriminator(Discriminator::create()->propertyName('Property name'))
//            ->readOnly()
//            ->writeOnly()
//            ->xml(Xml::create())
//            ->externalDocs(ExternalDocs::create()->url('https://example.com'))
//            ->example(['Venus'])
//            ->deprecated();
//
//        expect($schema->asArray())->toBe([
//            'title' => 'Schema title',
//            'description' => 'Schema description',
//            'default' => false,
//            'type' => 'boolean',
//            'nullable' => true,
//            'discriminator' => ['propertyName' => 'Property name'],
//            'readOnly' => true,
//            'writeOnly' => true,
//            'xml' => [],
//            'externalDocs' => ['url' => 'https://example.com'],
//            'example' => ['Venus'],
//            'deprecated' => true,
//        ]);
//    });
//
//    it('can create integer schema with all parameters', function (): void {
//        $schema = Schema::create('test')
//            ->title('Schema title')
//            ->description('Schema description')
//            ->default(false)
//            ->format(Schema::FORMAT_INT32)
//            ->type(Schema::TYPE_INTEGER)
//            ->maximum(100)
//            ->exclusiveMaximum(101)
//            ->minimum(1)
//            ->exclusiveMinimum(0)
//            ->multipleOf(2)
//            ->nullable()
//            ->discriminator(Discriminator::create()->propertyName('Property name'))
//            ->readOnly()
//            ->writeOnly()
//            ->xml(Xml::create())
//            ->externalDocs(ExternalDocs::create()->url('https://example.com'))
//            ->example(['Venus'])
//            ->deprecated();
//
//        expect($schema->asArray())->toBe([
//            'title' => 'Schema title',
//            'description' => 'Schema description',
//            'default' => false,
//            'format' => 'int32',
//            'type' => 'integer',
//            'maximum' => 100,
//            'exclusiveMaximum' => 101,
//            'minimum' => 1,
//            'exclusiveMinimum' => 0,
//            'multipleOf' => 2,
//            'nullable' => true,
//            'discriminator' => ['propertyName' => 'Property name'],
//            'readOnly' => true,
//            'writeOnly' => true,
//            'xml' => [],
//            'externalDocs' => ['url' => 'https://example.com'],
//            'example' => ['Venus'],
//            'deprecated' => true,
//        ]);
//    });
//
//    it('can create number schema with all parameters', function (): void {
//        $schema = Schema::create('test')
//            ->title('Schema title')
//            ->description('Schema description')
//            ->default(false)
//            ->type(Schema::TYPE_NUMBER)
//            ->maximum(100)
//            ->exclusiveMaximum(101)
//            ->minimum(1)
//            ->exclusiveMinimum(0)
//            ->multipleOf(2)
//            ->nullable()
//            ->discriminator(Discriminator::create()->propertyName('Property name'))
//            ->readOnly()
//            ->writeOnly()
//            ->xml(Xml::create())
//            ->externalDocs(ExternalDocs::create()->url('https://example.com'))
//            ->example(['Venus'])
//            ->deprecated();
//
//        expect($schema->asArray())->toBe([
//            'title' => 'Schema title',
//            'description' => 'Schema description',
//            'default' => false,
//            'type' => 'number',
//            'maximum' => 100,
//            'exclusiveMaximum' => 101,
//            'minimum' => 1,
//            'exclusiveMinimum' => 0,
//            'multipleOf' => 2,
//            'nullable' => true,
//            'discriminator' => ['propertyName' => 'Property name'],
//            'readOnly' => true,
//            'writeOnly' => true,
//            'xml' => [],
//            'externalDocs' => ['url' => 'https://example.com'],
//            'example' => ['Venus'],
//            'deprecated' => true,
//        ]);
//    });
//
//    it('can create object schema with all parameters', function (): void {
//        $property = Schema::string('id')->format(Schema::FORMAT_UUID);
//
//        $schema = Schema::create('test')
//            ->title('Schema title')
//            ->description('Schema description')
//            ->default(false)
//            ->type(Schema::TYPE_OBJECT)
//            ->required($property)
//            ->properties($property)
//            ->additionalProperties(Schema::integer('age'))
//            ->maxProperties(10)
//            ->minProperties(1)
//            ->nullable()
//            ->discriminator(Discriminator::create()->propertyName('Property name'))
//            ->readOnly()
//            ->writeOnly()
//            ->xml(Xml::create())
//            ->externalDocs(ExternalDocs::create()->url('https://example.com'))
//            ->example(['Venus'])
//            ->deprecated();
//
//        expect($schema->asArray())->toBe([
//            'title' => 'Schema title',
//            'description' => 'Schema description',
//            'default' => false,
//            'type' => 'object',
//            'required' => ['id'],
//            'properties' => [
//                'id' => [
//                    'format' => 'uuid',
//                    'type' => 'string',
//                ],
//            ],
//            'additionalProperties' => [
//                'type' => 'integer',
//            ],
//            'maxProperties' => 10,
//            'minProperties' => 1,
//            'nullable' => true,
//            'discriminator' => ['propertyName' => 'Property name'],
//            'readOnly' => true,
//            'writeOnly' => true,
//            'xml' => [],
//            'externalDocs' => ['url' => 'https://example.com'],
//            'example' => ['Venus'],
//            'deprecated' => true,
//        ]);
//    });
//
//    it('can create string schema with all parameters', function (): void {
//        $schema = Schema::create('test')
//            ->title('Schema title')
//            ->description('Schema description')
//            ->default(false)
//            ->format(Schema::FORMAT_UUID)
//            ->type(Schema::TYPE_STRING)
//            ->pattern('/[a-zA-Z]+/')
//            ->maxLength(10)
//            ->minLength(1)
//            ->nullable()
//            ->discriminator(Discriminator::create()->propertyName('Property name'))
//            ->readOnly()
//            ->writeOnly()
//            ->xml(Xml::create())
//            ->externalDocs(ExternalDocs::create()->url('https://example.com'))
//            ->example(['Venus'])
//            ->deprecated();
//
//        expect($schema->asArray())->toBe([
//            'title' => 'Schema title',
//            'description' => 'Schema description',
//            'default' => false,
//            'format' => 'uuid',
//            'type' => 'string',
//            'pattern' => '/[a-zA-Z]+/',
//            'maxLength' => 10,
//            'minLength' => 1,
//            'nullable' => true,
//            'discriminator' => ['propertyName' => 'Property name'],
//            'readOnly' => true,
//            'writeOnly' => true,
//            'xml' => [],
//            'externalDocs' => ['url' => 'https://example.com'],
//            'example' => ['Venus'],
//            'deprecated' => true,
//        ]);
//    });
//
//    it('can create array schema with ref', function (): void {
//        $reusableSchema = new class () extends ReusableSchemaFactory {
//            public function build(): Descriptor
//            {
//                return Schema::object('pet')
//                    ->properties(
//                        Schema::string('name'),
//                        Schema::string('tag'),
//                    )
//                    ->required('name');
//            }
//
//            public static function key(): string
//            {
//                return 'test';
//            }
//        };
//        $schema = Schema::array('array')
//            ->items($reusableSchema);
//
//        expect($schema->asArray())->toBe([
//            'type' => 'array',
//            'items' => [
//                '$ref' => '#/components/schemas/test',
//            ],
//        ]);
//    });
//
//    it('can create schemas using methods', function ($method, $expectation): void {
//        /** @var Schema $schema */
//        $schema = Schema::$method($method);
//
//        expect($schema->asArray())->toBe([
//            'type' => $expectation,
//        ]);
//    })->with([
//        'array' => ['array', Schema::TYPE_ARRAY],
//        'boolean' => ['boolean', Schema::TYPE_BOOLEAN],
//        'integer' => ['integer', Schema::TYPE_INTEGER],
//        'number' => ['number', Schema::TYPE_NUMBER],
//        'object' => ['object', Schema::TYPE_OBJECT],
//        'string' => ['string', Schema::TYPE_STRING],
//    ]);
// })->covers(Schema::class)->skip();
