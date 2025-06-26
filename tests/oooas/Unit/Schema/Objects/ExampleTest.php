<?php

namespace Tests\oooas\Unit\Schema\Objects;

use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Example;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Fields\Description;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Fields\ExternalValue;
use MohammadAlavi\ObjectOrientedOpenAPI\Schema\Objects\Example\Fields\Summary;

describe(class_basename(Example::class), function (): void {
    it('can be created', function (): void {
        $example = Example::create()
            ->summary(Summary::create('Summary ipsum'))
            ->description(Description::create('Description ipsum'))
            ->value('Value');

        $response = $example->asArray();

        expect($response)->toBe([
            'summary' => 'Summary ipsum',
            'description' => 'Description ipsum',
            'value' => 'Value',
        ]);
    });

    it('can be created with external value', function (): void {
        $example = Example::create()
            ->externalValue(
                ExternalValue::create('https://example.com/example.json'),
            );

        $response = $example->asArray();

        expect($response)->toBe([
            'externalValue' => 'https://example.com/example.json',
        ]);
    });

    it('prevents setting mutually exclusive values', function (): void {
        expect(fn () => Example::create()
            ->value('Value')
            ->externalValue(
                ExternalValue::create('https://example.com/example.json'),
            ))->toThrow(\InvalidArgumentException::class);
    });
})->covers(Example::class);
