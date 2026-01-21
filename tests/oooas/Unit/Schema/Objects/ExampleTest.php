<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;

describe(class_basename(Example::class), function (): void {
    describe('Group A fields (value/externalValue)', function (): void {
        it('can be created with value', function (): void {
            $example = Example::create()
                ->summary('Summary ipsum')
                ->description('Description ipsum')
                ->value('Value');

            $response = $example->compile();

            expect($response)->toBe([
                'summary' => 'Summary ipsum',
                'description' => 'Description ipsum',
                'value' => 'Value',
            ]);
        });

        it('can be created with external value', function (): void {
            $example = Example::create()
                ->externalValue('https://laragen.io/example.json');

            $response = $example->compile();

            expect($response)->toBe([
                'externalValue' => 'https://laragen.io/example.json',
            ]);
        });

        it('prevents setting value after externalValue', function (): void {
            expect(fn () => Example::create()
                ->externalValue('https://laragen.io/example.json')
                ->value('Value'))
                ->toThrow(\InvalidArgumentException::class, 'value and externalValue fields are mutually exclusive');
        });

        it('prevents setting externalValue after value', function (): void {
            expect(fn () => Example::create()
                ->value('Value')
                ->externalValue('https://laragen.io/example.json'))
                ->toThrow(\InvalidArgumentException::class, 'value and externalValue fields are mutually exclusive');
        });
    });

    describe('Group B fields (dataValue/serializedValue) - OAS 3.2+', function (): void {
        it('can be created with dataValue', function (): void {
            $example = Example::create()
                ->summary('User Example')
                ->dataValue(['id' => 123, 'name' => 'Alice']);

            expect($example->compile())->toBe([
                'summary' => 'User Example',
                'dataValue' => ['id' => 123, 'name' => 'Alice'],
            ]);
        });

        it('can be created with serializedValue', function (): void {
            $example = Example::create()
                ->summary('XML Example')
                ->serializedValue('<person><name>John</name></person>');

            expect($example->compile())->toBe([
                'summary' => 'XML Example',
                'serializedValue' => '<person><name>John</name></person>',
            ]);
        });

        it('can use dataValue and serializedValue together', function (): void {
            $example = Example::create()
                ->summary('Full Example')
                ->dataValue(['id' => 123, 'name' => 'Alice'])
                ->serializedValue('{"id":123,"name":"Alice"}');

            expect($example->compile())->toBe([
                'summary' => 'Full Example',
                'dataValue' => ['id' => 123, 'name' => 'Alice'],
                'serializedValue' => '{"id":123,"name":"Alice"}',
            ]);
        });

        it('can set serializedValue before dataValue', function (): void {
            $example = Example::create()
                ->serializedValue('{"id":123}')
                ->dataValue(['id' => 123]);

            expect($example->compile())->toBe([
                'dataValue' => ['id' => 123],
                'serializedValue' => '{"id":123}',
            ]);
        });
    });

    describe('Group A and Group B mutual exclusivity', function (): void {
        it('prevents setting value after dataValue', function (): void {
            expect(fn () => Example::create()
                ->dataValue(['id' => 123])
                ->value('Value'))
                ->toThrow(
                    \InvalidArgumentException::class,
                    'value/externalValue cannot be used together with dataValue/serializedValue',
                );
        });

        it('prevents setting value after serializedValue', function (): void {
            expect(fn () => Example::create()
                ->serializedValue('<xml/>')
                ->value('Value'))
                ->toThrow(
                    \InvalidArgumentException::class,
                    'value/externalValue cannot be used together with dataValue/serializedValue',
                );
        });

        it('prevents setting externalValue after dataValue', function (): void {
            expect(fn () => Example::create()
                ->dataValue(['id' => 123])
                ->externalValue('https://example.com/example.json'))
                ->toThrow(
                    \InvalidArgumentException::class,
                    'value/externalValue cannot be used together with dataValue/serializedValue',
                );
        });

        it('prevents setting externalValue after serializedValue', function (): void {
            expect(fn () => Example::create()
                ->serializedValue('<xml/>')
                ->externalValue('https://example.com/example.xml'))
                ->toThrow(
                    \InvalidArgumentException::class,
                    'value/externalValue cannot be used together with dataValue/serializedValue',
                );
        });

        it('prevents setting dataValue after value', function (): void {
            expect(fn () => Example::create()
                ->value('Value')
                ->dataValue(['id' => 123]))
                ->toThrow(
                    \InvalidArgumentException::class,
                    'dataValue/serializedValue cannot be used together with value/externalValue',
                );
        });

        it('prevents setting serializedValue after value', function (): void {
            expect(fn () => Example::create()
                ->value('Value')
                ->serializedValue('<xml/>'))
                ->toThrow(
                    \InvalidArgumentException::class,
                    'dataValue/serializedValue cannot be used together with value/externalValue',
                );
        });

        it('prevents setting dataValue after externalValue', function (): void {
            expect(fn () => Example::create()
                ->externalValue('https://example.com/example.json')
                ->dataValue(['id' => 123]))
                ->toThrow(
                    \InvalidArgumentException::class,
                    'dataValue/serializedValue cannot be used together with value/externalValue',
                );
        });

        it('prevents setting serializedValue after externalValue', function (): void {
            expect(fn () => Example::create()
                ->externalValue('https://example.com/example.xml')
                ->serializedValue('<xml/>'))
                ->toThrow(
                    \InvalidArgumentException::class,
                    'dataValue/serializedValue cannot be used together with value/externalValue',
                );
        });
    });
})->covers(Example::class);
