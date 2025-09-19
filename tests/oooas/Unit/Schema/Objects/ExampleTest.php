<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;

describe(class_basename(Example::class), function (): void {
    it('can be created', function (): void {
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

    it('prevents setting mutually exclusive values', function (): void {
        expect(fn () => Example::create()
            ->value('Value')
            ->externalValue(
                'https://laragen.io/example.json',
            ))->toThrow(\InvalidArgumentException::class);
    });
})->covers(Example::class);
