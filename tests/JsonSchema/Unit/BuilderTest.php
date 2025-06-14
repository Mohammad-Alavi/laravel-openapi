<?php

use MohammadAlavi\ObjectOrientedJSONSchema\Draft202012\LooseFluentDescriptor;

describe(class_basename(LooseFluentDescriptor::class), function (): void {
    it('should return constant value as is', function (mixed $value): void {
        $descriptor = LooseFluentDescriptor::create()->const($value);

        expect(\Safe\json_encode($descriptor))->toBe(
            \Safe\json_encode([
                '$schema' => 'https://json-schema.org/draft-2020-12/schema',
                'const' => $value,
            ]),
        );
    })->with([
        'test',
        1,
        true,
        null,
        false,
    ]);
})->covers(LooseFluentDescriptor::class);
